<?php
/**
 * Copyright (c) 2011-2013 Jan Tlapak <jan@mfcc.cz>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @category  ZendeskContact
 * @package   Mfcc
 * @author    Jan Tlapak <jan@mfcc.cz>
 * @copyright 2011-2013 Jan Tlapak
 * @license   http://www.opesource.org/licenses/mit-license.php MIT-License
 * @link      https://github.com/Tlapi
 */

namespace Noodle;

//use Zend\ModuleManager\ModuleManager;
//use Zend\EventManager\StaticEventManager;
use	Zend\Mvc\ModuleRouteListener;

use Zend\Mvc\MvcEvent;

/**
 * The Module-Provider
 */

class Module
{

	private $changedLayout;

	public function init($moduleManager)
	{
		// Remember to keep the init() method as lightweight as possible
		// Check system
		$events = $moduleManager->getEventManager();
		$events->attach('loadModules.post', array($this, 'modulesLoaded'));
	}

	public function modulesLoaded($e)
    {
        // This method is called once all modules are loaded.
        $moduleManager = $e->getTarget();
        $loadModules = $moduleManager->getModules();
        // check var_dump($loadedModules);
        // TODO move this somewhere
        $DIModules = array(
        	'EdpModuleLayouts',
        	'ZfcBase',
        	'ZfcUser',
        	'ZfcUserDoctrineORM',
        	'DoctrineModule',
        	'DoctrineORMModule',
        	'FileBank',
        	'WebinoImageThumb',
        	'AssetManager',
        );
        foreach($DIModules as $module){
        	if(!in_array($module, $loadModules)){
        		die('Module '.$module.' is not loaded!');
        	}
        }

        // check writeables
        // TODO from config
        $dirs = array(
        		'www_root/_cache',
        		'www_root/_data',
        		'data',
        );
        foreach($dirs as $dir){
	        if (!is_writable($dir)) {
	        	die($dir.' is not writeable!');
	        }
        }
    }

    public function onBootstrap($e)
    {
    	$eventManager        = $e->getApplication()->getEventManager();
        //$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'redirectUnauthedUsersEvent'));
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'));

        $sem = $eventManager->getSharedManager();
        // listen to 'dashboard.load' when triggered by the IndexController
        $sem->attach('Noodle\Controller\IndexController', 'dashboard.load', function($e) {
        	// load dashboard
        });

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig()
    {
    	return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
    	return array(
    			'Zend\Loader\StandardAutoloader' => array(
    					'namespaces' => array(
    							__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
    					),
    			),
    	);
    }

    public function getServiceConfig()
    {
    	return array(
    			'factories' => array(
    					'baseForm' => function ($sm) {
    						//$service1 = $sm->get('parentPagesService');
    						//$service2 = $sm->get('categoryService');
    						$form    = new \Noodle\Forms\Form;
    						$form->setServiceLocator($sm);
    						return $form;
    					},
    					'fileUploadHandlerService' => function ($serviceManager) {
    						$service = new \Noodle\Service\UploadHandler($serviceManager->get('FileBank'));
    						return $service;
    					},
    			),
    			'invokables' => array(
    					'modulesService' => '\Noodle\Service\ModulesService',
    					'formMapperService' => '\Noodle\Service\FormMapper',
    					'fileProcessingService' => '\Noodle\Service\FileProcessing',
    					'repositoriesService' => '\Noodle\Service\Repositories',
    					'entityGeneratorService' => '\Noodle\Service\EntityGenerator',
    					'thumbnailerService' => '\Noodle\Service\Thumbnailer',
    			),
    	);
    }

    public function getViewHelperConfig()
    {
    	return array(
    			'invokables' => array(
    					'formelement'       => 'Noodle\Form\View\Helper\FormElement',
    					'formPicture'     => 'Noodle\Form\View\Helper\FormPicture',
    					'moduleList'     => 'Noodle\View\Helper\ModuleList',
    					'noodlePicture'     => 'Noodle\View\Helper\NoodlePicture',
    			),
    	);

    }

    /**
     * Change layout on dispatch
     * @param MvcEvent $e
     */
    public function onDispatch(MvcEvent $e)
    {
    	$currentController = $e->getTarget();
    	if($this->changedLayout){
    		$currentController->layout($this->changedLayout);
    	} else {
    		//$currentController->layout('layout/layout.phtml');
    	}
    }
}
