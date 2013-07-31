<?php
// ./module/Application/src/Application/View/Helper/UserIcon.php
namespace Noodle\View\Helper\Dashboard;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class GoogleAnalytics extends AbstractHelper implements ServiceLocatorAwareInterface
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

    	// Request Google Analytics Access
    	if(isset($_GET['requestGoogleAnalyticsAccess'])){
    		$this->requestAccess();
    		die();
    	}

    	// Process GA oAuth complete response
    	if( isset($_GET['oauth_return']) )
    	{
    		$this->admin_handle_oauth_complete();
    	}

    	if(!$optionService->hasOption('ga_dashboard.oauth_token')) {

    		echo '<a href="?requestGoogleAnalyticsAccess">Request Access</a>';

    	} elseif(isset($_GET['ga_selected_account'])) {

    		// Save selected account option
    		$optionService->setOption('ga_dashboard.account_id', $_GET['ga_selected_account']);

    	} elseif(!$optionService->hasOption('ga_dashboard.account_id')) {
    		echo 'SELECT ACCOUNT:';
    		$ga = new \GALib('oauth', NULL, $optionService->getOption('ga_dashboard.oauth_token'), $optionService->getOption('ga_dashboard.oauthsecret'), '', 60);
    		echo '<ul>';
    		foreach($ga->account_query() as $key => $account){
    			echo '<li><a href="?ga_selected_account='.$key.'">'.$account.'</a></li>';
    		}
    		echo '</ul>';
    	} else {
    		$data = new \GADDataModel($optionService->getOption('ga_dashboard.oauth_token'), $optionService->getOption('ga_dashboard.oauthsecret'), $optionService->getOption('ga_dashboard.account_id'));

    		echo '<h3 style="margin-top: 25px; margin-bottom: 15px;">Google Analytics <span style="font-size: 12px; font-weight: normal">('.$data->start_date.' to '.$data->end_date.')</span></h3>';
    		echo '<div style="padding-bottom: 5px;">';
    		//echo $data->start_date.' to '.$data->end_date.'<br />';
    		echo '<img width="870" height="200" src="'.$data->create_google_chart_url(870, 200).'"/>';
    		echo '</div>';
    		?>
    		<div style="position: relative; padding-top: 5px;" class="ie_layout">
		      <h4 style="position: absolute; top: 6px; left: 10px; background-color: whitesmoke; padding-left: 5px; padding-right: 5px;">Base Stats</h4>
		      <hr style="border: solid #eee 1px"/><br/>
		    </div>

		    <div>
		      <div id="base-stats">
		      <div style="text-align: left;">
		        <div style="width: 50%; float: left;">
		          <table>
		            <tr><td align="right"><?php echo number_format($data->summary_data['value']['ga:visits']); ?></td><td></td><td>Visits</td></tr>
		            <tr><td align="right"><?php echo number_format($data->total_pageviews); ?></td><td></td><td>Pageviews</td></tr>
		            <tr><td align="right"><?php echo (isset($data->summary_data['value']['ga:visits']) && $data->summary_data['value']['ga:visits'] > 0) ? round($data->total_pageviews / $data->summary_data['value']['ga:visits'], 2) : '0'; ?></td><td></td><td>Pages/Visit</td></tr>
		          </table>
		        </div>
		        <div style="width: 50%; float: right;">
		          <table>
		            <tr><td align="right"><?php echo (isset($data->summary_data['value']['ga:entrances']) && $data->summary_data['value']['ga:entrances'] > 0) ? round($data->summary_data['value']['ga:bounces'] / $data->summary_data['value']['ga:entrances'] * 100, 2) : '0'; ?>%</td><td></td><td>Bounce Rate</td></tr>
		            <tr><td align="right"><?php echo (isset($data->summary_data['value']['ga:visits']) && $data->summary_data['value']['ga:visits']) ? $this->convert_seconds_to_time($data->summary_data['value']['ga:timeOnSite'] / $data->summary_data['value']['ga:visits']) : '00:00:00'; ?></td><td></td><td>Avg. Time on Site</td></tr>
		            <tr><td align="right"><?php echo (isset($data->summary_data['value']['ga:visits']) && $data->summary_data['value']['ga:visits'] > 0) ? round($data->summary_data['value']['ga:newVisits'] / $data->summary_data['value']['ga:visits'] * 100, 2) : '0'; ?>%</td><td></td><td>% New Visits</td></tr>
		          </table>
		        </div>
		        <br style="clear: both"/>
		      </div>
		      </div>

		    </div>

		    <div style="position: relative; padding-top: 5px;" class="ie_layout">
		      <h4 style="position: absolute; top: 6px; left: 10px; background-color: whitesmoke; padding-left: 5px; padding-right: 5px;">Extended Stats</h4>
		      <hr style="border: solid #eee 1px"/><br/>
		    </div>

		    <div>
		      <div id="extended-stats">
		        <div style="text-align: left; font-size: 90%;">
		          <div style="width: 50%; float: left;">

		            <h4 class="heading">Top Views</h4>

		            <div style="padding-top: 5px;">
		              <?php
		                  $z = 0;
		                  foreach($data->pages as $page)
		                  {
		                    $url = $page['value'];
		                    $title = $page['children']['value'];
		                    $page_views = $page['children']['children']['ga:pageviews'];
		                    echo '<a href="' . $url . '">' . $title . '</a><br/> <div style="color: #666; padding-left: 5px; padding-bottom: 5px; padding-top: 2px;">' . $page_views . ' views</div>';
		                    $z++;
		                    if($z > 10) break;
		                  }
		              ?>
		            </div>
		          </div>

		          <div style="width: 50%; float: right;">
		            <h4 class="heading">Top Searches</h4>

		            <div style="padding-top: 5px; padding-bottom: 15px;">
		              <table width="100%">
		                <?php
		                    $z = 0;
		                    foreach($data->keywords as $keyword => $count)
		                    {
		                      if($keyword != "(not set)")
		                      {
		                        echo '<tr>';
		                        echo '<td>' . $count . '</td><td>&nbsp;</td><td> ' . $keyword . '</td>';
		                        echo '</tr>';
		                        $z++;
		                      }
		                      if($z > 10) break;
		                    }
		                ?>
		              </table>
		            </div>

		            <h4 class="heading">Top Referers</h4>

		            <div style="padding-top: 5px;">
		              <table width="100%">
		                <?php
		                    $z = 0;
		                    foreach($data->sources as $source => $count)
		                    {
		                      echo '<tr>';
		                      echo '<td>' . $count . '</td><td>&nbsp;</td><td> ' . $source . '</td>';
		                      echo '</tr>';
		                      $z++;
		                      if($z > 10) break;
		                    }
		                ?>
		              </table>
		            </div>
		          </div>
		          <br style="clear: both"/>
		        </div>
		      </div>

		    </div>
    		<?php
    		//var_dump($data);
    	}

    	//die("");
    	//var_dump($oa_response);
    }

    public function requestAccess()
    {
    	$signature_method = new \GADOAuthSignatureMethod_HMAC_SHA1();
    	$params = array();

    	$params['oauth_callback'] = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'].'?page=google-analytics-dashboard/gad-admin-options.php&oauth_return=true';
    	$params['scope'] = 'https://www.googleapis.com/auth/analytics.readonly'; // This is a space seperated list of applications we want access to
    	$params['xoauth_displayname'] = 'Noodle Analytics Dashboard';

    	$consumer = new \GADOAuthConsumer('anonymous', 'anonymous', NULL);
    	$req_req = \GADOAuthRequest::from_consumer_and_token($consumer, NULL, 'GET', 'https://www.google.com/accounts/OAuthGetRequestToken', $params);
    	$req_req->sign_request($signature_method, $consumer, NULL);

    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $req_req->to_url());
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    	$oa_response = curl_exec($ch);

    	if(curl_errno($ch))
    	{
    		$error_message = curl_error($ch);
    		$info_redirect = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'].'?page=google-analytics-dashboard/gad-admin-options.php&error_message=' . urlencode($error_message);
    		header("Location: " . $info_redirect);
    		die("");
    	}

    	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    	if($http_code == 200)
    	{
    		$access_params = $this->split_params($oa_response);

    		//add_option('gad_oa_anon_token', $access_params['oauth_token']);
    		//add_option('gad_oa_anon_secret', $access_params['oauth_token_secret']);
    		$_SESSION['gad_oa_anon_token'] = $access_params['oauth_token'];
    		$_SESSION['gad_oa_anon_secret'] = $access_params['oauth_token_secret'];

    		header("Location: https://www.google.com/accounts/OAuthAuthorizeToken?oauth_token=" . urlencode($access_params['oauth_token']));
    	}
    	else
    	{
    		$info_redirect = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'].'?page=google-analytics-dashboard/gad-admin-options.php&error_message=' . urlencode($oa_response);
    		header("Location: " . $info_redirect);
    	}
    }

    public function admin_handle_oauth_complete()
    {
    	// step two in oauth login process

    	$signature_method = new \GADOAuthSignatureMethod_HMAC_SHA1();
    	$params = array();

    	$params['oauth_verifier'] = $_REQUEST['oauth_verifier'];

    	$consumer = new \GADOAuthConsumer('anonymous', 'anonymous', NULL);

    	$upgrade_token = new \GADOAuthConsumer($_SESSION['gad_oa_anon_token'], $_SESSION['gad_oa_anon_secret']);

    	$acc_req = \GADOAuthRequest::from_consumer_and_token($consumer, $upgrade_token, 'GET', 'https://www.google.com/accounts/OAuthGetAccessToken', $params);

    	$acc_req->sign_request($signature_method, $consumer, $upgrade_token);

    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $acc_req->to_url());
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    	$oa_response = curl_exec($ch);

    	if(curl_errno($ch))
    	{
    		$error_message = curl_error($ch);
    		$info_redirect = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'].'?page=google-analytics-dashboard/gad-admin-options.php&error_message=' . urlencode($error_message);
    		header("Location: " . $info_redirect);
    		die("");
    	}

    	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    	unset($_SESSION['gad_oa_anon_token']);
    	unset($_SESSION['gad_oa_anon_secret']);
    	//delete_option('gad_oa_anon_token');
    	//delete_option('gad_oa_anon_secret');

    	if($http_code == 200)
    	{
    		$access_params = $this->split_params($oa_response);

    		//update_option('gad_oauth_token', $access_params['oauth_token']);
    		//update_option('gad_oauth_secret', $access_params['oauth_token_secret']);
    		//$this->gad_oauth_token = $access_params['oauth_token'];
    		//$this->gad_oauth_secret = $access_params['oauth_token_secret'];
    		//update_option('gad_auth_token', 'gad_see_oauth');

    		$optionService = $this->getServiceLocator()->getServiceLocator()->get('noodleOptions');

    		$optionService->setOption('ga_dashboard.oauth_token', $access_params['oauth_token']);
    		$optionService->setOption('ga_dashboard.oauthsecret', $access_params['oauth_token_secret']);

    		$info_redirect = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'].'?page=google-analytics-dashboard/gad-admin-options.php&info_message=' . urlencode('Authenticated!');
    		header("Location: " . $info_redirect);
    	}
    	else
    	{
    		$info_redirect = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REDIRECT_URL'].'?page=google-analytics-dashboard/gad-admin-options.php&error_message=' . urlencode($oa_response);
    		header("Location: " . $info_redirect);
    	}

    	die("");
    }

    public function split_params($response)
    {
    	$params = array();
    	$param_pairs = explode('&', $response);
    	foreach($param_pairs as $param_pair)
    	{
    		if (trim($param_pair) == '') { continue; }
    		list($key, $value) = explode('=', $param_pair);
    		$params[$key] = urldecode($value);
    	}
    	return $params;
    }

    /**
     * Takes a time in seconds and turns it into a string with the format
     * of hours:minutes:seconds
     *
     * @return string in the format hours:minutes:seconds
     */
    public function convert_seconds_to_time($time_in_seconds)
    {
    	$hours = floor($time_in_seconds / (60 * 60));
    	$minutes = floor(($time_in_seconds - ($hours * 60 * 60)) / 60);
    	$seconds = $time_in_seconds - ($minutes * 60) - ($hours * 60 * 60);

    	return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
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