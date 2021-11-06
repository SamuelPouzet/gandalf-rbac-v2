<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 06/11/2020
 * Time: 20:41
 */

namespace Rbac\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package Rbac\Entity
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{

    const USER_NOT_ACTVATED = 0;
    const USER_ACTVATED = 1;
    const USER_INACTIVE = 2;
    const USER_RETIRED = 3;


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
     * @var string
     * @ORM\Column(name="firstname")
     */
    protected $firstname;

    /**
     * @var string
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(name="avatar")
     */
    protected $avatar;

    /**
     * @var string
     * @ORM\Column(name="login")
     */
    protected $login;

    /**
     * @var string
     * @ORM\Column(name="password")
     */
    protected $password;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(name="date_create")
     */
    protected $dateCreate;

    /**
     * @var int
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @var string
     */
    protected $avatarPath = null;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }

    public function getArrayCopy(): array
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
     * @return User
     */
    public function setId(int $id): User
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
     * @return User
     */
    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return User
     */
    public function setAvatar(string $avatar): User
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     * @return User
     */
    public function setFirstname(string $firstname): User
    {
        $this->firstname = $firstname;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     * @return User
     */
    public function setLogin(string $login): User
    {
        $this->login = $login;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateCreate(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->dateCreate);
    }

    /**
     * @param \DateTimeImmutable $dateCreate
     * @return User
     */
    public function setDateCreate(\DateTimeImmutable $dateCreate): User
    {
        $this->dateCreate = $dateCreate->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return User
     */
    public function setStatus(int $status): User
    {
        $this->status = $status;
        return $this;
    }

}