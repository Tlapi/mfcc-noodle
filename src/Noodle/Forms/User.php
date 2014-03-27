<?php
namespace Noodle\Forms;

//use Zend\Captcha\AdapterInterface as CaptchaAdapter;
use Zend\Form\Form;

class User extends Form
{

	public function prepareElements()
	{
		// add() can take either an Element/Fieldset instance,
		// or a specification, from which the appropriate object
		// will be built.

        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => 'E-mail',
            	'appendText' => '@'
            ),
        	'type' => 'Zend\Form\Element\Email',
        	'attributes' => array(
        		'type' => 'text'
        	),
        ));
        
        $this->add(array(
            'name' => 'display_name',
            'options' => array(
                'label' => 'Name'
            ),
        	'attributes' => array(
        		'type' => 'text'
        	),
        ));

        $this->add(array(
            'name' => 'password',
            'options' => array(
                'label' => 'Password',
            ),
        	'type' => 'Zend\Form\Element\Password',
            'attributes' => array(
            	'maxlength' => '128'
            ),
        ));

        $this->add(array(
            'name' => 'role',
            'options' => array(
                'label' => 'Role',
                'value_options' => array(
                    1 => 'Administrator',
                    2 => 'Moderator',
                    3 => 'User',
                    4 => 'Guest'
                )
            ),
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'multiple' => 'multiple',
            ),
        ));

        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Add',
                'type'  => 'submit'
            ),
        ));

		// We could also define the input filter here, or
		// lazy-create it in the getInputFilter() method.
	}
	
	public function addGenerateAndSendPasswordField()
	{
		$this->add(array(
				'name' => 'generate',
				'options' => array(
						'label' => 'Generate and send new password'
				),
				'type' => 'Zend\Form\Element\Checkbox'
		));
	}
}