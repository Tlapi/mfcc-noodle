<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Noodle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractController
{
    public function indexAction()
    {
    	//$modules = $this->getServiceLocator()->get('modulesService')->getModules();
		/*
    	$authService = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');

    	$adapter = $authService->getAdapter();
    	$adapter->setIdentityValue('jan@potentus.com');
    	$adapter->setCredentialValue(md5('hovno'));
    	$authResult = $authService->authenticate();

    	var_dump($authResult);*/

    	$this->getEventManager()->trigger('dashboard.load', $this);

        return new ViewModel(array(
			//'modules' => $modules
        ));
    }
}
