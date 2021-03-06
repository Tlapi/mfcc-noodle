<?php

namespace Noodle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Entity()
 * @ORM\Table(name="user")
 * @property integer $id
 */
class User
{
	/**
     * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 * @var int
     */
    protected $user_id;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $display_name;

    /**
     * @ORM\Column(type="string");
     * @var string
     */
    protected $password;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToMany(targetEntity="Noodle\Entity\Role")
     * @ORM\JoinTable(name="user_role_linker",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="user_id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    protected $roles;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->user_id;
    }

    /**
     * Set id.
     *
     * @param int $id
     * @return UserInterface
     */
    public function setId($id)
    {
        $this->user_id = (int) $id;
        return $this;
    }

    /**
     * Get username.
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     * @return UserInterface
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     * @return UserInterface
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Get displayName.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->name;
    }

    /**
     * Set displayName.
     *
     * @param string $displayName
     * @return UserInterface
     */
    public function setDisplayName($displayName)
    {
        $this->name = $displayName;
        return $this;
    }

    /**
     * Generate password
     */
    public function generatePassword()
    {
    	$newPassword = $this->randPasswd();
    	$this->setPassword(md5($newPassword));
    	return $newPassword;
    }
    
    /**
     * Get password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password.
     *
     * @param string $password
     * @return UserInterface
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * Get state.
     *
     * @return int
     */
    public function getState()
    {
        //return $this->state;
    }

    /**
     * Set state.
     *
     * @param int $state
     * @return UserInterface
     */
    public function setState($state)
    {
        //$this->state = $state;
        //return $this;
    }
    
    /**
     * Get role.
     *
     * @return array
     */
    public function getRoles()
    {
    	return $this->roles->getValues();
    }

    /**
     * Reset role.
     *
     * @return array
     */
    public function resetRoles()
    {
        return $this->roles = null;
    }
    
    /**
     * Add a role to the user.
     *
     * @param Role $role
     *
     * @return void
     */
    public function addRole($role)
    {
    	$this->roles[] = $role;
    }
    
    /**
     * Generate password
     * @param number $length
     * @param string $chars
     * @return string
     */
    public function randPasswd( $length = 12, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789' ) {
    	return substr( str_shuffle( $chars ), 0, $length );
    }
    
    public function getArrayCopy()
    {
    	return get_object_vars($this);
    }
    
    public function exchangeArray($data)
    {
    	$this->email = (isset($data['email']))     ? $data['email']     : null;
    	$this->display_name = (isset($data['display_name']))     ? $data['display_name']     : null;
    	$this->password = (isset($data['password']))     ? md5($data['password'])     : null;
    }
}
