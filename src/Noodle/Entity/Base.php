<?php
namespace Noodle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

/**
* @ORM\MappedSuperclass
* @ORM\HasLifecycleCallbacks
*/
class Base
{

    public $isHidden = false;

    /**
	* @ORM\Id
	* @ORM\Column(type="integer");
	* @ORM\GeneratedValue(strategy="AUTO")
	* @Annotation\Exclude()
	*/
	private $id;

	/**
	 * @ORM\Column(type="integer", nullable=true);
	 * @Annotation\Exclude()
	 */
	private $parent_row_id;

	/**
	 * @ORM\Column(type="integer", nullable=true);
	 * @Annotation\Exclude()
	 */
	private $parent_entity;

	/**
	 * @ORM\PrePersist
	 */
	public function setOrderIdOnPrePersist()
	{

	}
	
	/**
	 * ID getter
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Parent row id setter and getter
	 */
	public function getParentRowId()
	{
		return $this->parent_row_id;
	}
	public function setParentRowId($parent_row_id)
	{
		$this->parent_row_id = $parent_row_id;
		return $this->parent_row_id;
	}
	
	/**
	 * Parent entity setter and getter
	 */
	public function getParentEntity()
	{
		return $this->parent_entity;
	}
	public function setParentEntity($entity)
	{
		$this->parent_entity = $entity;
		return $this->parent_entity;
	}

}
