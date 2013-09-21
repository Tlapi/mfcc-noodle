<?php
// module/Settings/src/Settings/Controller/SettingsController.php:
namespace Noodle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;

//use Zend\Form\Annotation\AnnotationBuilder;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class SettingsController extends AbstractActionController
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
		// Get entity repository
		$module = $this->getEntityManager()->getRepository('Noodle\Entity\Settings');
		
		$form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm('Noodle\Entity\Settings');
		
		// Get entity
		$entity = $module->find(1);
		
		if($entity)
			$form->bind($entity);
		
		// Process post request
		if ($this->request->isPost()) {
		
			// process files first
			//$post = $this->getServiceLocator()->get('fileProcessingService')->processFiles($this->request);
		
			$form->setData($this->request->getPost());
			if ($form->isValid()) {
		
				// map data to entity
				$entity = $this->getServiceLocator()->get('formMapperService')->mapFormDataToEntity($form, $entity);
		
				// persist entity
				$this->getEntityManager()->persist($entity);
				$this->getEntityManager()->flush();
		
				// redirect
				$this->flashMessenger()->addMessage('Changes saved!');
				return $this->redirect()->toRoute('noodle/settings');
			} else {
				//die('invalid');
			}
		
		}
		
		// layout variables
		$this->layout()->header = 'Settings';
		
		return new ViewModel(array(
				'form' => $form,
				'id' => 1,
				'flashMessages' => $this->flashMessenger()->getMessages()
		));
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