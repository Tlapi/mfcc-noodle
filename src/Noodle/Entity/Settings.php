<?php
namespace Noodle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;
//use Noodle\Entity\Base;

/**
 * A movie
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("Settings")
 * @ORM\Entity(repositoryClass="\Noodle\Repository\Base")
 * @ORM\Table(name="settings")
 * @property integer $id
 * @property string $name
 */
class Settings
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @Annotation\Exclude()
	 */
	public $id;
	
	/**
	* @ORM\Column(type="string");
	* @Annotation\Type("Zend\Form\Element\Text")
	* @Annotation\Options({"label":"Project name"})
	* @Annotation\Required(true)
	*/
	public $project_name;

	/**
	* @ORM\Column(type="string");
	* @Annotation\Type("Zend\Form\Element\Text")
	* @Annotation\Options({"label":"Google analytics"})
	*/
	public $ga_code;

	/**
	* @ORM\Column(type="string");
	* @Annotation\Type("Zend\Form\Element\Text")
	* @Annotation\Options({"label":"Facebook ID"})
	*/
	public $facebook_id;

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
