<?php
namespace Modules\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\Form\Annotation\AnnotationBuilder;

class EntityGenerator implements ServiceLocatorAwareInterface
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
	 * Generate and save entity
	 */
	public function generateEntity($post)
	{

		$fieldTypes = $this->getServiceLocator()->get('config')['noodle']['field_types'];

		$generateEntity = '<?php
namespace Modules\Entity\Tables;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;
use Modules\Entity\Base;

/**
* '.$post['name'].'
*
* @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
* @Annotation\Name("'.$post['name'].'")
* @ORM\Entity(repositoryClass="\Modules\Repository\Base")
* @ORM\Table(name="'.strtolower($post['table_name']).'")';


			foreach($post['column_name'] as $column){
				$generateEntity .= '
* @property integer $'.$column;
			}

			$generateEntity .= '
*/
class '.ucfirst($post['table_name']).' extends Base
{

	/**
	* @ORM\Id
	* @ORM\Column(type="integer");
	* @ORM\GeneratedValue(strategy="AUTO")
	* @Annotation\Exclude()
	*/
	public $id;

	/**
	* @ORM\Column(type="integer");
	* @Annotation\Exclude()
	*/
	public $parent_row_id;

	/**
	* @ORM\Column(type="integer");
	* @Annotation\Exclude()
	*/
	public $parent_entity;';

			$i = 0;
			foreach($post['column_name'] as $column){
				$generateEntity .= '

	/**
	* @ORM\Column(type="'.$fieldTypes[$post['field_type'][$i]]['doctrine_type'].'");
	* @Annotation\Type("'.$post['field_type'][$i].'")
	* @Annotation\Options({"label":"'.$post['field_title'][$i].'", "listed":'.($post['listed'][$i]?'true':'false').','.($post['placeholder'][$i]?'"placeholder":"'.$post['placeholder'][$i].'",':'').($post['block_help'][$i]?'"blockHelp":"'.$post['block_help'][$i].'",':'').'})
	* @Annotation\Required('.($post['required'][$i]?'true':'false').')
	*/
	public $'.$column.';
	';
				$i++;
			}

			$generateEntity .= '

	/**
	* Magic getter to expose protected properties.
	*
	* @param DateTime $property
	* @return mixed
	*/
	public function __get($property)
	{
		return $this->$property;
	}

	/**
	* Magic setter to save protected properties.
	*
	* @param string $property
	* @param mixed $value
	*/
	public function __set($property, $value)
	{
		$this->$property = $value;
	}

}';

			return $generateEntity;
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