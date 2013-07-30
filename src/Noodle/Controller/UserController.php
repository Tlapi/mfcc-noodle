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

		$data = $this->getRequest()->getPost();

		if(sizeof($data)){

			$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

			$adapter = $authService->getAdapter();
			$adapter->setIdentityValue($data->email);
			$adapter->setCredentialValue(md5($data->password));
			$authResult = $authService->authenticate();

			if ($authResult->isValid()) {
				return $this->redirect()->toRoute('noodle');
			}
		}
		$this->layout('noodle/layout/layout-login');
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