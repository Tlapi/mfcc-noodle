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