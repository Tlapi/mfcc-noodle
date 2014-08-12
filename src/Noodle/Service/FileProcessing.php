<?php
namespace Noodle\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Form\Annotation\AnnotationBuilder;

/**
 * Class FileProcessing
 * @package Noodle\Service
 */
class FileProcessing implements ServiceLocatorAwareInterface
{
    protected $serviceLocator;

	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

    /**
     * Process files posted by form
     *
     * @param $request
     * @return mixed
     */
    public function processFiles($request) {

		$post = $request->getPost();
		$fileBank = $this->getServiceLocator()->get('FileBank');
		foreach($request->getFiles() as $key => $file){
			if (isset($file[0]['tmp_name']) and !empty($file[0]['tmp_name'])) {
				$fileEntity = $fileBank->save($file['tmp_name']);
				$post->$key = $fileEntity->getId();
			} else {
				$post->$key = null;
			}
		}
		return $post;
	}

    /**
     * setServiceLocator
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @see \Zend\ServiceManager\ServiceLocatorAwareInterface::setServiceLocator()
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
	}

    /**
     * getServiceLocator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator() {
		return $this->serviceLocator;
	}

    /**
     * setEntityManager
     *
     * @param EntityManager $em
     */
    public function setEntityManager(EntityManager $em)
	{
		$this->em = $em;
	}

    /**
     * getEntityManager
     *
     * @return array|\Doctrine\ORM\EntityManager|object
     */
    public function getEntityManager()
	{
		if (null === $this->em) {
			$this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		}
		return $this->em;
	}
}