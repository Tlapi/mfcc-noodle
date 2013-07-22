<?php
namespace Noodle\Entity\Tables;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;
use Noodle\Entity\Base;

/**
 * A movie
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("RelationTest")
 * @ORM\Entity(repositoryClass="\Noodle\Repository\Base")
 * @ORM\Table(name="noodle_relation")
 * @property integer $id
 * @property string $title
 * @property string $description
 */
class Relation
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
	public $parent_entity;

	/**
	* @ORM\Column(type="string");
	* @Annotation\Type("Zend\Form\Element\Text")
	* @Annotation\Options({"listed": true,"label":"Title:"})
	* @Annotation\Required(true)
	*/
	public $title;

	/**
     * @ORM\ManyToMany(targetEntity="\Noodle\Entity\Tables\Test")
     * @ORM\JoinTable(name="noodle_modules",
     *      joinColumns={@ORM\JoinColumn(name="select_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id", referencedColumnName="select_id")}
     *      )
     * @Annotation\Exclude()
     */
    private $modules;

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

}
