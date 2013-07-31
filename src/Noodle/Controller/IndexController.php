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

class IndexController extends AbstractActionController
{
	
	/**
	 * @var Modules for dashboard
	 */
	protected $dashboardModules = array();
	
    public function indexAction()
    {
    	$this->getEventManager()->trigger('dashboard', $this);
    	
    	//$this->dashboardModules = 'ahoj';
    	//exit();

        return new ViewModel(array(
			//'modules' => $modules
			'dashboardModules' => $this->dashboardModules
        ));
    }
    
    public function addDashboardModule($module)
    {
    	$this->dashboardModules[] = $module;
    }
    
    public function getDashboardModules()
    {
    	return $this->dashboardModules;
    }
    
}
