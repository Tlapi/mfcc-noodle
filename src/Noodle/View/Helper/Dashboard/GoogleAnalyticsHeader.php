<?php
// ./module/Application/src/Application/View/Helper/UserIcon.php
namespace Noodle\View\Helper\Dashboard;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GoogleAnalyticsHeader extends AbstractHelper implements ServiceLocatorAwareInterface
{

	private $gad_oa_anon_token;
	private $gad_oa_anon_secret;

	private $gad_oauth_token;
	private $gad_oauth_secret;

	public function __invoke()
    {
    	// TODO render templates

    	//return $this->getServiceLocator()->getServiceLocator()->get('modulesService')->getModules();
    	$optionService = $this->getServiceLocator()->getServiceLocator()->get('noodleOptions');

    	if(!$optionService->hasOption('ga_dashboard.account_id')) {
    		?>
    			<div class="col-md-6 col-sm-6 stat">
                    <div class="data">
                        <span class="number">N/A</span>
                        visits
                    </div>
                    <span class="date">Today</span>
                </div>
                <div class="col-md-6 col-sm-6 stat">
                    <div class="data">
                        <span class="number">N/A</span>
                        users
                    </div>
                    <span class="date"><?php echo date('M')?> <?php echo date('Y')?></span>
                </div>
    		<?php 
    	} else {
    		$data = new \GADDataModel($optionService->getOption('ga_dashboard.oauth_token'), $optionService->getOption('ga_dashboard.oauthsecret'), $optionService->getOption('ga_dashboard.account_id'));
    		?>
    			<div class="col-md-6 col-sm-6 stat">
                    <div class="data">
                        <span class="number"><?php echo number_format(end($data->daily_pageviews)); ?></span>
                        visits
                    </div>
                    <span class="date">Today</span>
                </div>
                <div class="col-md-6 col-sm-6 stat">
                    <div class="data">
                        <span class="number"><?php echo number_format($data->summary_data['value']['ga:visits']); ?></span>
                        users
                    </div>
                    <span class="date">Last 30 days</span>
                </div>

    		<?php
    		var_dump($data);
    	}

    	//die("");
    	//var_dump($oa_response);
    }

    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
    	$this->serviceLocator = $serviceLocator;
    	return $this;
    }
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
    	return $this->serviceLocator;
    }
}