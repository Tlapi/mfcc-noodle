<?php
// module/Settings/src/Settings/Controller/SettingsController.php:
namespace Noodle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;

class UserController extends AbstractActionController
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
	 * Login user action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function loginAction()
	{

		//var_dump($this->zfcUserAuthentication()->hasIdentity());
		$this->layout('noodle/layout/layout-login');

		try {
			$this->getEntityManager()->getConnection()->connect();
		} catch (\Exception $e) {
			// failed to connect
			die('No db connection');
		}
		
		// Check if user table is not empty, if so create new admin user
		$users = $this->getEntityManager()->getRepository('Noodle\Entity\User');
		if(!$users->findAll()){
			//die('No admins found!');
			return $this->redirect()->toRoute('noodle/user/create-admin');
		}
		
		$data = $this->getRequest()->getPost();

		$errors = null;
		if(sizeof($data)){

			$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

			$adapter = $authService->getAdapter();
			$adapter->setIdentityValue($data->email);
			$adapter->setCredentialValue(md5($data->password));
			$authResult = $authService->authenticate();

			if ($authResult->isValid()) {
				return $this->redirect()->toRoute('noodle');
			}
			$errors = $authResult->getMessages();
		}
		
		return new ViewModel(array(
				'errors' => $errors
		));
	}

	/**
	 * Create user action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function createAdminAction()
	{
		$this->layout('noodle/layout/layout-login');
		
		// Check if user table is not empty, if so redirect to login page
		$users = $this->getEntityManager()->getRepository('Noodle\Entity\User');
		if($users->findAll()){
			$this->redirect()->toRoute('noodle/user/login');
		}
		
		$form = new \Noodle\Forms\User();
		$form->prepareElements();
		$form->setInputFilter(new \Noodle\Forms\UserFilter());
		
		$messages = null;
		$request = $this->getRequest();
		if ($request->isPost()){
			$newAdmin = new \Noodle\Entity\User();
			$form->bind($newAdmin);
			$data = $request->getPost();
		
			$form->setData($data);
			if ($form->isValid()) {
				$this->getEntityManager()->persist($newAdmin);
				$this->getEntityManager()->flush();
		
				$this->redirect()->toRoute('noodle/user/login');
			} else {
				$messages = $form->getMessages();
			}
		}
		
		return new ViewModel(array(
				'form' => $form,
				'messages' => $messages,
		));
	}
	
	/**
	 * Login user action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function logoutAction()
	{

		//var_dump($this->zfcUserAuthentication()->hasIdentity());

		//$this->layout('noodle/layout/layout-login');

		$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

		$authService->getStorage()->clear();

		return $this->redirect()->toRoute('noodle/user/login');
	}

	/**
	 * Manage users
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function manageAction()
	{
		// Get entity repository
		$users = $this->getEntityManager()->getRepository('Noodle\Entity\User');
		
		return new ViewModel(array(
				'users' => $users->findAll()
		));
	}
	
	/**
	 * Add user
	 * @see Zend\Mvc\Controller.AbstractActionController::editAction()
	 */
	public function addAction()
	{
		// Get entity repository
		$users = $this->getEntityManager()->getRepository('Noodle\Entity\User');
		
		$form = new \Noodle\Forms\User();
		$form->prepareElements();
		$form->addGenerateAndSendPasswordField();
		
		$request = $this->getRequest();
		if ($request->isPost()){
			$newAdmin = new \Noodle\Entity\User();
			$form->bind($newAdmin);
			$data = $request->getPost();
			
			$form->setData($data);
			if ($form->isValid()) {
				$this->getEntityManager()->persist($newAdmin);
				$this->getEntityManager()->flush();
			
				$this->redirect()->toRoute('noodle/user/manage');
			} else {
				$messages = $form->getMessages();
			}
		}
		
		return new ViewModel(array(
				'form' => $form
		));
	}
	
	/**
	 * Edit user
	 * @see Zend\Mvc\Controller.AbstractActionController::editAction()
	 */
	public function editAction()
	{
		$id = (string) $this->params()->fromRoute('id', 0);
		
		// Get entity repository
		$users = $this->getEntityManager()->getRepository('Noodle\Entity\User');
		$user = $users->find($id);
		$form = new \Noodle\Forms\User();
		$form->prepareElements();
		
		$form->bind($user);
		
		$request = $this->getRequest();
		if ($request->isPost()){
				
			$form->setData($this->request->getPost());
			if ($form->isValid()) {
				$this->getEntityManager()->persist($user);
				$this->getEntityManager()->flush();
					
				$this->redirect()->toRoute('noodle/user/manage');
			} else {
				$messages = $form->getMessages();
			}
		}
		
		return new ViewModel(array(
				'form' => $form
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