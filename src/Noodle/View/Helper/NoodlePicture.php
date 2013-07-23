<?php
// ./module/Application/src/Application/View/Helper/UserIcon.php
namespace Noodle\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NoodlePicture extends AbstractHelper implements ServiceLocatorAwareInterface
{

	public function __invoke($id, $maxWidth = null, $maxHeight = null, $crop = false)
    {
    	$fileBank = $this->getServiceLocator()->get('FileBank');
    	$thumbnailer = $this->getServiceLocator()->getServiceLocator()->get('thumbnailerService');

    	$file = $fileBank->getFileById($id);

    	return $thumbnailer->getThumbnailUrl($file, $maxWidth, $maxHeight, $crop);
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