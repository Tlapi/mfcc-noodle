<?php
// module/Modules/src/Modules/Controller/ModulesController.php:
namespace Noodle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;

use Doctrine\ORM\Tools\SchemaTool;

use Zend\Form\Annotation\AnnotationBuilder;

class ModulesManagerController extends AbstractActionController
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
		return new ViewModel(array(
				//'repositories' => $this->getServiceLocator()->get('repositoriesService')->getRepositories(),
				'flashMessages' => $this->flashMessenger()->getMessages()
		));
	}

	/**
     * Add module action
     * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
     */
        public function addAction()
    {
        $form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm('Noodle\Entity\Module');

        $builder = new AnnotationBuilder();
        $schema = new SchemaTool($this->getEntityManager());
        $cmf = $this->getEntityManager()->getMetadataFactory();


        //var_dump($schema->getCreateSchemaSql($classes));

        // process form
        if ($this->request->isPost()) {

            $form->setData($this->request->getPost());
            if ($form->isValid()) {

                // map data to entity
                $entity = $this->getServiceLocator()->get('formMapperService')->mapFormDataToEntity($form, new \Noodle\Entity\Module());

                // persist entity
                $this->getEntityManager()->persist($entity);
                $this->getEntityManager()->flush();

                // redirect
                $this->flashMessenger()->addMessage('Module created!');
                return $this->redirect()->toRoute('noodle/modules-manager');
            } else {
                //die('invalid');
                //var_dump($form->)
            }

        }



		return new ViewModel(array(
				'form' => $form
		));
	}

    /**
     * Edit module action
     * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
     */
    public function editAction()
    {
        $id = (string) $this->params()->fromRoute('id', 0);

        // Get entity repository
        $modules = $this->getEntityManager()->getRepository('Noodle\Entity\Module');
        // Get entity
        $module = $modules->find($id);

        $form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm('Noodle\Entity\Module');

        $form->setData($module->getArrayCopy());
       // var_dump($form);
        //die();

        //var_dump($schema->getCreateSchemaSql($classes));

        // process form
        if ($this->request->isPost()) {

            foreach($this->request->getPost() as $key => $val)
            {   $data[$key] = $val;

            }
            $data['entity']=$module->getEntity();

            $form->setData($data);

            if ($form->isValid()) {

                //die('valid sad');
                // map data to entity
                $module->populate($form->getData());

                // persist entity
                $this->getEntityManager()->persist($module);
                $this->getEntityManager()->flush();

                // redirect
                $this->flashMessenger()->addMessage('Module changed!');
                return $this->redirect()->toRoute('noodle/modules-manager');
            } else {
                //die('invalid');

            }

        }

        return new ViewModel(array(
            'form' => $form,
            'id' => $id
        ));
    }
	
	/**
	 * Delete module action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function deleteAction()
	{
		$id = (string) $this->params()->fromRoute('id', 0);
		
		$modules = $this->getEntityManager()->getRepository('Noodle\Entity\Module');
		$module = $modules->find($id);
		$this->getEntityManager()->remove($module);
		$this->getEntityManager()->flush();
				
		// redirect
		$this->flashMessenger()->addMessage('Module deleted!');
		return $this->redirect()->toRoute('noodle/modules-manager');
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