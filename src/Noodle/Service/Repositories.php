<?php
namespace Noodle\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Form\Annotation\AnnotationBuilder;

class Repositories implements ServiceLocatorAwareInterface
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

	public function getRepositories()
	{
		$builder = new AnnotationBuilder();

		if ($handle = opendir('module/Modules/src/Modules/Entity/Tables')) { // TODO this to config
			while (false !== ($entry = readdir($handle))) {
				if($entry!="." && $entry!=".."){
					$className = 'Modules\Entity\Tables\\'.str_replace('.php', '', $entry);
					$entity = new $className;
					$options[str_replace('Modules\Entity\Tables\\', '', get_class($entity))] = $builder->getFormSpecification($entity)['name'];
				}
			}
			closedir($handle);
		}

		return $options;
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