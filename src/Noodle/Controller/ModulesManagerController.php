<?php
// module/Modules/src/Modules/Controller/ModulesController.php:
namespace Noodle\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Zend\View\Model\JsonModel;
use Doctrine\ORM\EntityManager;

use Doctrine\ORM\Tools\SchemaTool;

use Zend\Form\Annotation\AnnotationBuilder;

class ModulesManagerController extends AbstractActionController
{

	/**
	 * @var Doctrine\ORM\EntityManager
	 */
	protected $em;

	/**
	 * Void index action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function indexAction()
	{
		return new ViewModel(array(
				//'repositories' => $this->getServiceLocator()->get('repositoriesService')->getRepositories(),
		));
	}

	/**
	 * Add module action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function addAction()
	{
		$form = $this->getServiceLocator()->get('formMapperService')->setupEntityForm('Noodle\Entity\Module');

		$builder = new AnnotationBuilder();
		$schema = new SchemaTool($this->getEntityManager());
		$cmf = $this->getEntityManager()->getMetadataFactory();


		//var_dump($schema->getCreateSchemaSql($classes));

		// process form
		if ($this->request->isPost()) {

			$form->setData($this->request->getPost());
			if ($form->isValid()) {

				// map data to entity
				$entity = $this->getServiceLocator()->get('formMapperService')->mapFormDataToEntity($form, new \Noodle\Entity\Module());

				// persist entity
				$this->getEntityManager()->persist($entity);
				$this->getEntityManager()->flush();

				// redirect
				$this->flashMessenger()->addMessage('Module created!');
				return $this->redirect()->toRoute('noodle/modules-manager');
			} else {
				//die('invalid');
				//var_dump($form->)
			}

		}



		return new ViewModel(array(
				'form' => $form,
		));
	}

	/**
	 * Add module action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function addRepositoryAction()
	{
		$config = $this->getServiceLocator()->get('config');

		if ($this->request->isPost()) {

			$post = $this->request->getPost();

			$generateEntity = $this->getServiceLocator()->get('entityGeneratorService')->generateEntity($post);

			// Save $generateEntity

			//echo $generateEntity;

			// TODO change location of table entities ???

			file_put_contents('module/Modules/src/Modules/Entity/Tables/'.ucfirst($post['table_name']).'.php', $generateEntity);

			//$schema = new SchemaTool($this->getEntityManager());
			//$cmf = $this->getEntityManager()->getMetadataFactory();
			//var_dump($schema->getCreateSchemaSql(array($cmf->getMetadataFor('Modules/Entity/Tables/'.ucfirst($post['table_name'])))));

		}

		return new ViewModel(array(
			'fieldTypes' => $config['noodle']['field_types']
		));

		/*
		 * $table = $schema->createTable('customer_evernote');
        $table->addOption('type', 'INNODB');
        $table->addOption('charset', 'utf8');
        $table->addOption('collate', 'utf8_unicode_ci');

        // Columns.
        $table->addColumn('customer_id', 'bigint', array(
            'length'    => 20,
            'notnull'   => true,
            'autoincrement' => false));
        $table->addColumn('integration_date', 'datetime', array('notnull' => true));
        $table->addColumn('oauth_token', 'string', array(
            'length'    => 255,
            'notnull'   => true));
        $table->addColumn('oauth_shard_id', 'string', array(
            'length'    => 4,
            'notnull'   => true,
            'fixed'     => true));

        $table->setPrimaryKey(array('customer_id'), 'pk_customer_id');
        $table->addForeignKeyConstraint($schema->getTable('customer'), array('customer_id'), array('id'));

        $schema->dropTable('customer_evernote');
		 */
	}

	/**
	 * Edit repository action
	 * @see Zend\Mvc\Controller.AbstractActionController::indexAction()
	 */
	public function editRepositoryAction()
	{
		$name = (string) $this->params()->fromRoute('name', 0);

		$config = $this->getServiceLocator()->get('config');

		$repositoryClassname = 'Modules\Entity\Tables\\'.$name;
		$repository = new $repositoryClassname;

		$builder = new AnnotationBuilder();
		$entity = $builder->getFormSpecification($repository);

		return new ViewModel(array(
			'fieldTypes' => $config['noodle']['field_types'],
			'entity' => $entity,
			'metadata' => $this->getEntityManager()->getClassMetadata($repositoryClassname)
		));

		/*
		 * $table = $schema->createTable('customer_evernote');
        $table->addOption('type', 'INNODB');
        $table->addOption('charset', 'utf8');
        $table->addOption('collate', 'utf8_unicode_ci');

        // Columns.
        $table->addColumn('customer_id', 'bigint', array(
            'length'    => 20,
            'notnull'   => true,
            'autoincrement' => false));
        $table->addColumn('integration_date', 'datetime', array('notnull' => true));
        $table->addColumn('oauth_token', 'string', array(
            'length'    => 255,
            'notnull'   => true));
        $table->addColumn('oauth_shard_id', 'string', array(
            'length'    => 4,
            'notnull'   => true,
            'fixed'     => true));

        $table->setPrimaryKey(array('customer_id'), 'pk_customer_id');
        $table->addForeignKeyConstraint($schema->getTable('customer'), array('customer_id'), array('id'));

        $schema->dropTable('customer_evernote');
		 */
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