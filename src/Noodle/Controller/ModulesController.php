<?php
// module/Modules/src/Modules/Controller/ModulesController.php:
namespace Noodle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;

//use Zend\Form\Annotation\AnnotationBuilder;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use Doctrine\ORM\Tools\SchemaTool;

use Zend\Form\Annotation\AnnotationBuilder;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class ModulesController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * Void index action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function indexAction()
	{

	}

	/**
	 * Show action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function showAction()
	{
        // Get name of entity
		$name = (string) $this->params()->fromRoute('name', 0);

		// Get module by name
		$moduleName = $this->getServiceLocator()->get('modulesService')->getModule($name);
        
        $config = $this->getServiceLocator()->get('config');

		// Get entity repository
		$module = $this->getEntityManager()->getRepository($config['noodle']['entity_namespace'].'\\'.$name);

        // Get entity form
		$form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm($config['noodle']['entity_namespace'].'\\'.$name);

        $formOptions = $form->getOptions();

		$listed = array();

		// Get listed fields
		foreach($form->getElements() as $element){
			if($element->getOption('listed')){
				$listed[] = $element;
			}
		}

        // Get filtering parameters
        $filterParams=array();
        $filters = $moduleName[0]->filters;
        if($filters)
        {   $filters = explode(',',str_replace(' ','', $filters));
            foreach($filters as $filter)
            {   $element=$form->get($filter);
                
                //for filtering by date create two fields - From and To
                if($element->getAttributes()['type']=='datetime')
                {   //var_dump($filter);
                    if($this->params()->fromQuery($filter.'_nooto')!==null && $this->params()->fromQuery($filter.'_nooto')!="")
                    {   $date = new \DateTime($this->params()->fromQuery($filter.'_nooto'));
                        $filterParams[]='u.'.$filter.' <= \''.mysql_escape_string($date->format('y-m-d')).'\'';
                    }
                    if($this->params()->fromQuery($filter.'_noofrom')!==null && $this->params()->fromQuery($filter.'_noofrom')!="")
                    {   $date = new \DateTime($this->params()->fromQuery($filter.'_noofrom'));
                        $filterParams[]='u.'.$filter.' >= \''.mysql_escape_string($date->format('y-m-d')).'\'';
                    }
                    $element->setValue($this->params()->fromQuery($filter.'_noofrom').' - '.$this->params()->fromQuery($filter.'_nooto'));
                    $element->setOption('format','d.m.Y');
                    $element->setOption('only-date','');
                }
                else
                {   if($this->params()->fromQuery($filter)!==null && $this->params()->fromQuery($filter)!="")
                    {   $filterParams[]='u.'.$filter.' = \''.mysql_escape_string($this->params()->fromQuery($filter)).'\'';
                    }
                    $element->setValue($this->params()->fromQuery($filter));
                }
                
                
                $element->setAttribute('required',false);
                
        
            }
        }
        else $filters = array();
        
        //Preserve parts of query string
        $queryPreserve="";
        $queryPreserveVars=array();
        $preserve = array_merge(array('page','order','dir'),$filters);
        if(sizeof($preserve))
        {   foreach($preserve as $filter)
            {   try {
                    $element=$form->get($filter);
                }
                catch (\Exception $e)
                {   $element = null;
                }
                
                // for datetime preserve from and to
                if($element && $element->getAttributes()['type']=='datetime')
                {   $queryPreserveVars[]=$filter."_nooto=".$this->params()->fromQuery($filter."_nooto");
                    $queryPreserveVars[]=$filter."_noofrom=".$this->params()->fromQuery($filter."_noofrom");
                }
                // for other types preserve only one value
                else
                {   $queryPreserveVars[]=$filter."=".$this->params()->fromQuery($filter);
                }
            }
            $queryPreserve = implode('&',$queryPreserveVars);
        }
        if($queryPreserve!="") $queryPreserve.='&';
        
        
		// Set ordering
		$orderElement = null;
		$orderColumn = (string)$this->params()->fromQuery('order');
		$orderDirection = (string)$this->params()->fromQuery('dir');
		if($orderColumn){
			$orderElement = $form->get($orderColumn);
		} elseif(isset($formOptions['orderColumn'])) {
            $orderElement = $form->get($formOptions['orderColumn']);
            $orderDirection = $formOptions['orderDirection'];
        }

		// Set pagination
        //var_dump($filterParams);
		$adapter = new DoctrineAdapter(new ORMPaginator($module->findModuleItems($orderElement, $orderDirection, implode(' AND ',$filterParams))));
		$paginator = new Paginator($adapter);
		$paginator->setDefaultItemCountPerPage(10);

		$page = (int)$this->params()->fromQuery('page');
		if($page){
			$paginator->setCurrentPageNumber($page);
		} else {
			$page = 1;
		}

		// Layout variables
		$this->layout()->header = $moduleName[0]->module_name;
		$this->layout()->moduleName = $name;

		return new ViewModel(array(
				'listed' => $listed,
				'name' => $name,
				'paginator' => $paginator,
				'form' => $form,
				'page' => $page,
				'dir' => $orderDirection,
				'moduleName' => $moduleName[0],
                'filters' => $filters,
                'flashMessages' => $this->flashMessenger()->getMessages(),
                'queryPreserve' => $queryPreserve
		));

	}

	/**
	 * Edit action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function editAction()
	{
        $name = (string) $this->params()->fromRoute('name', 0);
		$id = (string) $this->params()->fromRoute('id', 0);
		
		$config = $this->getServiceLocator()->get('config');

		// Get module name
		$moduleName = $this->getServiceLocator()->get('modulesService')->getModule($name);

		// Get entity repository
		$module = $this->getEntityManager()->getRepository($config['noodle']['entity_namespace'].'\\'.$name);

		$form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm($config['noodle']['entity_namespace'].'\\'.$name);

		// Get entity
		$entity = $module->find($id);

		$form->bind($entity);
		//var_dump($entity->title);
		//var_dump($form->get('title')->getValue());
		
		// get inversed to rows
		$cmf = $this->getEntityManager()->getMetadataFactory();
		$inversedModulesData = array();
		foreach($cmf->getMetadataFor($config['noodle']['entity_namespace'].'\\'.$name)->associationMappings as $inversedModule){
			if(isset($inversedModule['joinTable']["inverseJoinColumns"])){
				//echo $module['targetEntity'];
				//$inversedModulesData[]
				$inversedModuleEntity = $this->getEntityManager()->getRepository($inversedModule['targetEntity']);
				$inversedModuleRows = $inversedModuleEntity->findModuleItems(null, null, 'u.'.$inversedModule['joinTable']["inverseJoinColumns"][0]['referencedColumnName'].' = '.$id)->getResult();
				$inversedModulesData[$inversedModule['targetEntity']] = $inversedModuleRows;
			}
		}

		// Process post request
		if ($this->request->isPost()) {

            $roles = array(); $u_roles = $this->identity()->getRoles();foreach($u_roles as $role){$roles[] = $role->getRoleId();}
            if(!in_array('Administrator', $roles) && $moduleName[0]->getChangeProtected()){
                die('Access Denied');
            }
			// process files first
			//$post = $this->getServiceLocator()->get('fileProcessingService')->processFiles($this->request);

			$form->setData($this->request->getPost());
			if ($form->isValid()) {

				// map data to entity
				$entity = $this->getServiceLocator()->get('formMapperService')->mapFormDataToEntity($form, $entity);

                // Find datetime fields
                foreach($form->getElements() as $element){
                    if(get_class($element)=='Zend\Form\Element\Date' || get_class($element)=='Zend\Form\Element\DateTime'){
                        $elementName = $element->getName();
                        $entity->$elementName = new \DateTime($element->getValue());
                    }
                }

				// persist entity
				$this->getEntityManager()->persist($entity);
				$this->getEntityManager()->flush();

				// redirect
				$this->flashMessenger()->addMessage('Changes saved!');
				return $this->redirect()->toRoute('noodle/modules/show', array('name' => $name));
			} else {
				//die('invalid');
				var_dump($form->getMessages());
			}

		}

		// Layout variables
		$this->layout()->header = $moduleName[0]->module_name . ' - edit';
		$this->layout()->moduleName = $name;

		return new ViewModel(array(
                'moduleName' => $moduleName[0],
                'form' => $form,
				'name' => $name,
				'id' => $id,
				'inversedModulesData' => $inversedModulesData
		));
	}

	/**
	 * Edit sheet action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function sheetAction()
	{
		$parentEntityName = (string) $this->params()->fromRoute('name', 0);
		$sheetName = (string) $this->params()->fromRoute('sheet_name', 0);
		$id = (string) $this->params()->fromRoute('id', 0);
		
		$config = $this->getServiceLocator()->get('config');

		// Get parent form
		$formParent = $this->getServiceLocator()->get('formMapperService')->setupEntityForm($config['noodle']['entity_namespace'].'\\'.$parentEntityName);

		// Get entity repository
		$sheets = $formParent->getOption('sheets');
		$module = $this->getEntityManager()->getRepository($sheets[$sheetName]->getOption('targetEntity'));

		$entityClassname = $sheets[$sheetName]->getOption('targetEntity');
		$form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm($entityClassname);

		// Process post request
		if ($this->request->isPost()) {

			// process files first
			$post = $this->getServiceLocator()->get('fileProcessingService')->processFiles($this->request);

			$form->setData($post);
			if ($form->isValid()) {

				// map data to entity
				$entity = $this->getServiceLocator()->get('formMapperService')->mapFormDataToEntity($form, $entity);

				// sheet spicific parameters
				$entity->setParentEntity('Modules\Entity\\'.$parentEntityName);
				$entity->setParentRowId($id);

				$this->getEntityManager()->persist($entity);
				$this->getEntityManager()->flush();

				// redirect
				$this->flashMessenger()->addMessage('Entity saved!');
				return $this->redirect()->toRoute('modules/edit/sheet', array('name' => $parentEntityName, 'id' => $id,'sheet_name' => $sheetName));
			} else {
				//die('invalid');
			}

		}

		$data = $module->findBy(array('parent_entity' => $config['noodle']['entity_namespace'].'\\'.$parentEntityName, 'parent_row_id' => $id));

		return new ViewModel(array(
				'formParent' => $formParent,
				'form' => $form,
				'data' => $data,
				'entity' => $entityClassname,
				'sheetName' => $sheetName,
				'id' => $id,
				'parentEntityName' => $parentEntityName,
				'flashMessages' => $this->flashMessenger()->getMessages()
		));
	}

	/**
	 * Add action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function addAction()
	{
		$config = $this->getServiceLocator()->get('config');
		
		// Get name of entity
		$name = (string) $this->params()->fromRoute('name', 0);
		$entityClassname = $config['noodle']['entity_namespace'].'\\'.$name;

		// Get module name
		$moduleName = $this->getServiceLocator()->get('modulesService')->getModule($name);

		// Get entity repository
		$module = $this->getEntityManager()->getRepository($config['noodle']['entity_namespace'].'\\'.$name);

		// Setup entity form
		$form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm($config['noodle']['entity_namespace'].'\\'.$name);

        $form->setData((new $entityClassname())->getArrayCopy());

		if ($this->request->isPost()) {

			// process files first
			$post = $this->getServiceLocator()->get('fileProcessingService')->processFiles($this->request);

			$form->setData($post);
			if ($form->isValid()) {

				// map data to entity
                $entity = new $entityClassname();
                
				$entity = $this->getServiceLocator()->get('formMapperService')->mapFormDataToEntity($form, $entity);

                // Find datetime fields
                foreach($form->getElements() as $element){
                    if(get_class($element)=='Zend\Form\Element\Date' || get_class($element)=='Zend\Form\Element\DateTime'){
                        $elementName = $element->getName();
                        $entity->$elementName = new \DateTime($element->getValue());
                    }
                }
                
                //var_dump($entity);
                //die();
                // persist entity
				$this->getEntityManager()->persist($entity);
				$this->getEntityManager()->flush();

                // Find order fields and set current id to that fields
                foreach($form->getElements() as $element){
                    if(get_class($element)=='Noodle\Form\Element\OrderId'){
                        $elementName = $element->getName();
                        $entity->$elementName = $entity->getId();
                        $this->getEntityManager()->persist($entity);
                        $this->getEntityManager()->flush();
                    }
                }

				// redirect
				$this->flashMessenger()->addMessage('Entity saved!');
				return $this->redirect()->toRoute('noodle/modules/show', array('name' => $name));
			} else {
				//die('invalid');
			}

		}

		// Layout variables
		$this->layout()->header = $moduleName[0]->module_name . ' - add';
		$this->layout()->moduleName = $name;

		return new ViewModel(array(
				'form' => $form
		));
	}

	/**
	 * Delete action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function deleteAction()
	{
		$config = $this->getServiceLocator()->get('config');
		
		$name = (string) $this->params()->fromRoute('name', 0);
		$id = (string) $this->params()->fromRoute('id', 0);

		// Get entity repository
		$module = $this->getEntityManager()->getRepository($config['noodle']['entity_namespace'].'\\'.$name);

		$form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm($config['noodle']['entity_namespace'].'\\'.$name);

		// Get entity
		$entity = $module->find($id);

		// delete
		// $this->getEntityManager()->remove($entity);
        $entity->deleted=1;
        $this->getEntityManager()->persist($entity);

		// Handle related entities
		$post = $this->request->getPost();
		// get inversed to rows
		$cmf = $this->getEntityManager()->getMetadataFactory();
		$inversedModulesData = array();
		foreach($cmf->getMetadataFor($config['noodle']['entity_namespace'].'\\'.$name)->associationMappings as $inversedModule){
			if(isset($inversedModule['joinTable']["inverseJoinColumns"])){
				//echo $module['targetEntity'];
				//$inversedModulesData[]
				$inversedModuleEntity = $this->getEntityManager()->getRepository($inversedModule['targetEntity']);
				$inversedModuleRows = $inversedModuleEntity->findModuleItems(null, null, 'u.'.$inversedModule['joinTable']["inverseJoinColumns"][0]['referencedColumnName'].' = '.$id)->getResult();
				foreach($inversedModuleRows as $row){
					if($post['relationHandle']=='setnull'){
						$row->{$inversedModule['joinTable']["inverseJoinColumns"][0]['referencedColumnName']} = null;
					}
					if($post['relationHandle']=='delete'){
						//$this->getEntityManager()->remove($row);
                        $row->deleted=1;
                        $this->getEntityManager()->persist($row);
					}
				}
			}
		}

		$this->getEntityManager()->flush();

		// redirect
		$this->flashMessenger()->addMessage('Entity deleted!');
		return $this->redirect()->toRoute('noodle/modules/show', array('name' => $name));
	}

    /**
     * Save order action
     * @see Zend\Mvc\Controller.AbstractActionController::saveOrderAction()
     */
    public function saveOrderAction()
    {
        $config = $this->getServiceLocator()->get('config');

        $name = (string) $this->params()->fromRoute('name', 0);
        $id = (string) $this->params()->fromRoute('id', 0);

        // Get entity repository
        $module = $this->getEntityManager()->getRepository($config['noodle']['entity_namespace'].'\\'.$name);

        foreach($_POST['json'] as $item){
            $row = $module->find(intval($item['id']));
            // TODO configurable
            $row->order_id = $item['order'];
            $this->getEntityManager()->persist($row);
        }

        $this->getEntityManager()->flush();

        echo 'ok';
        exit();
    }

	/**
	 * Mass delete action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function massDeleteAction()
	{
		$config = $this->getServiceLocator()->get('config');
		
		$name = (string) $this->params()->fromRoute('name', 0);

		// Get entity repository
		$module = $this->getEntityManager()->getRepository($config['noodle']['entity_namespace'].'\\'.$name);

		$form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm($config['noodle']['entity_namespace'].'\\'.$name);

		// Get entities
		$post = $this->request->getPost();

		foreach($post['ids'] as $id){
			// Get entity
			$entity = $module->find($id);

			// delete
			$this->getEntityManager()->remove($entity);

			// Handle related entities
			// get inversed to rows
			$cmf = $this->getEntityManager()->getMetadataFactory();
			$inversedModulesData = array();
			foreach($cmf->getMetadataFor($config['noodle']['entity_namespace'].'\\'.$name)->associationMappings as $inversedModule){
				if(isset($inversedModule['joinTable']["inverseJoinColumns"])){
					$inversedModuleEntity = $this->getEntityManager()->getRepository($inversedModule['targetEntity']);
					$inversedModuleRows = $inversedModuleEntity->findModuleItems(null, null, 'u.'.$inversedModule['joinTable']["inverseJoinColumns"][0]['referencedColumnName'].' = '.$id)->getResult();
					foreach($inversedModuleRows as $row){
						$row->{$inversedModule['joinTable']["inverseJoinColumns"][0]['referencedColumnName']} = null;
					}
				}
			}
		}

		$this->getEntityManager()->flush();

		// redirect
		$this->flashMessenger()->addMessage('Entities deleted!');
		return $this->redirect()->toRoute('noodle/modules/show', array('name' => $name));
	}

	public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}
	public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}

}