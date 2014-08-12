<?php
namespace Noodle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Zend\Form\Annotation;

/**
 * A movie
 *
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("Module")
 * @ORM\Entity
 * @ORM\Table(name="modules")
 * @property integer $id
 * @property string $module_name
 */
class Module
{

	/**
	* @ORM\Id
	* @ORM\Column(type="integer");
	* @ORM\GeneratedValue(strategy="AUTO")
	* @Annotation\Exclude()
	*/
	private $id;

	/**
	* @ORM\Column(type="string");
	* @Annotation\Type("Zend\Form\Element\Text")
	* @Annotation\Options({"label":"Module name:", "listed":true})
	* @Annotation\Required(true)
	*/
	private $module_name;


	/**
	* @ORM\Column(type="string");
	* @Annotation\Type("Zend\Form\Element\Text")
	* @Annotation\Required(true)
	* @Annotation\Options({"label":"Repository class:", "listed":true})
	*/
	private $entity;

    /**
     * @ORM\Column(type="string", nullable=true);
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Icon:", "listed":true})
     */
    private $icon;

    /**
     * @ORM\Column(type="integer", nullable=true);
     * @Annotation\Type("Zend\Form\Element\Checkbox")
     * @Annotation\Options({"label":"Protected:", "listed":true})
     */
    private $change_protected;
    
    /**
     * @ORM\Column(type="string", nullable=true);
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Filters:", "listed":false})
     */
    private $filters;

	/**
	* Magic getter to expose protected properties.
	*
	* @param DateTime $property
	* @return mixed
	*/

	public function __get($property)
	{   return $this->$property;
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

    public function getIcon()
    {
        if($this->icon) {
            return $this->icon;
        } else {
            return 'icon-edit';
        }
    }

    public function getEntity()
    {
        return $this->entity;
    }

    public function getChangeProtected()
    {
        return $this->change_protected;
    }

    public function getArrayCopy()
    {
        $data = get_object_vars($this);
        return $data;
    }

    public function populate($data = array())
    {
        foreach($data as $key => $val)
        {  $this->$key = $data[$key];

        }

    }

}
