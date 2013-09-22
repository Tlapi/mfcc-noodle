<?php
// module/Modules/src/Modules/Controller/ModulesController.php:
namespace Noodle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;

//use Zend\Form\Annotation\AnnotationBuilder;

use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

use Doctrine\ORM\Tools\SchemaTool;

use Zend\Form\Annotation\AnnotationBuilder;

use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class VendorModulesController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * Show action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function showAction()
	{
		$key = (int) $this->params()->fromRoute('key', 0);
		$vendorModules = $this->getServiceLocator()->get('modulesService')->getVendorModules();
		$module = $vendorModules[$key];
		return new ViewModel(array(
				'render' => $module->render()
		));
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