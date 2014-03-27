<?php
namespace Noodle\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Mail;

class Mailer implements ServiceLocatorAwareInterface
{

	protected $serviceLocator;

	public function __construct()
	{
		// construct
	}

	
	public function sendMail($to, $subject, $body){
		$mail = new Mail\Message();
		$mail->setBody($body);
		$mail->setFrom('info@mfcc.cz', 'Noodle CMS');
		$mail->addTo($to, $to);
		$mail->setSubject($subject);
		
		$transport = new Mail\Transport\Sendmail();
		$transport->send($mail);
	}

	/**
	 * Interface methods
	 * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
	 */
	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

}