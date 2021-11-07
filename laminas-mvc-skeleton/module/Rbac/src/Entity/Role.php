<?php

namespace Rbac\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class Role
 * @package Rbac\Entity
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class Role
{

    const ROLE_ACTIVE = 1;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(name="id")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="name")
     */
    protected $name;

    /**
     * @var int
     * @ORM\Column(name="is_active")
     */
    protected $is_active;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="User", mappedBy="users")
     */
    protected $users;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="children")
     * @ORM\JoinTable(name="role_hierarchy",
     *   joinColumns={@ORM\JoinColumn(name="id_parent", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="id_child", referencedColumnName="id")}
     * )
     */
    protected $parents;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Role", mappedBy="parents")
     */
    protected $children;

    /**
     * @var Collection
     * @ORM\ManyToMany(targetEntity="Permission", inversedBy="roles", cascade={"persist", "merge"})
     * @ORM\JoinTable(name="role_privilege",
     *   joinColumns={@ORM\JoinColumn(name="id_role", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="id_privilege", referencedColumnName="id")}
     * )
     */
    protected $privileges;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->parents = new ArrayCollection();
        $this->children = new ArrayCollection();
        $this->privileges = new ArrayCollection();
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Role
     */
    public function setId(int $id): Role
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Role
     */
    public function setName(string $name): Role
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getIsActive(): int
    {
        return $this->is_active;
    }

    /**
     * @param int $is_active
     * @return Role
     */
    public function setIsActive(int $is_active): Role
    {
        $this->is_active = $is_active;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * @param Collection $users
     * @return Role
     */
    public function setUsers(Collection $users): Role
    {
        $this->users = $users;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getParents(): Collection
    {
        return $this->parents;
    }

    /**
     * @param Collection $parents
     * @return Role
     */
    public function setParents(Collection $parents): Role
    {
        $this->parents = $parents;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    /**
     * @param Collection $children
     * @return Role
     */
    public function setChildren(Collection $children): Role
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getPrivileges(): Collection
    {
        return $this->privileges;
    }

    /**
     * @param Collection $privileges
     * @return Role
     */
    public function setPrivileges(Collection $privileges): Role
    {
        $this->privileges = $privileges;
        return $this;
    }

}