<?php
namespace Noodle\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Form\Annotation\AnnotationBuilder;

class FileProcessing implements ServiceLocatorAwareInterface
{

	protected $serviceLocator;

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	public function __construct()
	{
		// construct
	}

	/**
	 * Process files posted by form
	 */
	public function processFiles($request) {

		$post = $request->getPost();

		$fileBank = $this->getServiceLocator()->get('FileBank');
		foreach($request->getFiles() as $key => $file){
			if($file['tmp_name']){
				$fileEntity = $fileBank->save($file['tmp_name']);
				$post->$key = $fileEntity->getId();
			} else {
				$post->$key = null;
			}
		}

		return $post;
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