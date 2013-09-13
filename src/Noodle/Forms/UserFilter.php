<?php
namespace Noodle\Forms;


use Zend\InputFilter\InputFilter;

class UserFilter extends InputFilter
{
	public function __construct()
	{
		$this->add(array(
				'name'       => 'email',
				'required'   => true,
				'filters'   => array(
						array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'EmailAddress',
						'options' => array(
								'encoding' => 'UTF-8',
								'min'      => 5,
								'max'      => 255,
						)
					)
				)
		));
		
		$this->add(array(
				'name'       => 'display_name',
				'required'   => true,
				'filters'   => array(
						array('name' => 'StringTrim'),
				)
		));
		
		$this->add(array(
				'name'       => 'password',
				'required'   => true,
				'filters'   => array(
						array('name' => 'StringTrim'),
				),
				'validators' => array(
					array(
						'name' => 'StringLength',
						'options' => array(
								'encoding' => 'UTF-8', 
                            	'min'      => 5, 
                            	'max'      => 128,
						)
					)
				)
		));

	}
}