<?php

namespace Rbac\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Permission
 * @package Rbac\Entity
 * @ORM\Entity(repositoryClass="Rbac\Repository\PermissionRepository")
 * @ORM\Table(name="privilege")
 */
class Permission
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @ORM\Column(name="is_active")
     */
    protected $is_active;

    /**
     * @ORM\Column(name="description")
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="permissions", cascade={"persist", "merge", "remove"})
     */
    protected $roles;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Privilege
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Privilege
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getisActive()
    {
        return $this->is_active;
    }

    /**
     * @param mixed $is_active
     * @return Privilege
     */
    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Privilege
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function addRole(Role $role):Privilege
    {
        $this->roles[] = $role;
        return $this;
    }

    public function getRoles():ArrayCollection
    {
        return $this->roles;
    }

    public function razRoles():Privilege
    {
        $this->roles = new ArrayCollection();
        return $this;
    }
}