<?php
namespace Noodle\Entity\Tables;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;
use Noodle\Entity\Base;

/**
 * A movie
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("Test")
 * @ORM\Entity(repositoryClass="\Noodle\Repository\Base")
 * @ORM\Table(name="noodle_news")
 * @property integer $id
 * @property string $name
 */
class News extends Base
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
	* @Annotation\Options({"label":"Nadpis", "listed":true})
	* @Annotation\Required(true)
	*/
	public $title;

	/**
	* @ORM\Column(type="string");
	* @Annotation\Type("Zend\Form\Element\Text")
	* @Annotation\Options({"label":"Štítek", "listed":true})
	* @Annotation\Required(true)
	*/
	public $label;

	/**
	* @ORM\Column(type="string");
	* @Annotation\Type("Zend\Form\Element\Select")
	* @Annotation\Options({"label":"Místo", "listed":true})
	* @Annotation\Attributes({"options":{"1":"Bajkazyl","2":"Náplavka","3":"Žižkov","4":"Meetfactory"}})
	*/
	public $place;

	/**
	* @ORM\Column(type="string");
	* @Annotation\Type("Zend\Form\Element\Textarea")
	* @Annotation\Options({"label":"Text"})
	* @Annotation\Required(true)
	*/
	public $text;

	/**
	* @ORM\Column(type="integer");
	* @Annotation\Type("Noodle\Form\Element\Picture")
	* @Annotation\Options({"label":"Obrázek"})
	* @Annotation\Required(false)
	*/
	public $picture;

	/**
	 * @Annotation\Options({"label":"My sheet", "sheetType": "cyclic", "targetEntity":"\Noodle\Entity\Tables\Relation"})
	 * @Annotation\Required(false)
	 */
	public $sheet;

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
