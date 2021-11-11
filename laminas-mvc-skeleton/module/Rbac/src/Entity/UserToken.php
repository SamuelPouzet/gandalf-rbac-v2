<?php

namespace Rbac\Entity;
use Doctrine\ORM\Mapping as ORM;
use Laminas\Form\Element\Collection;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_token")
 */
class UserToken
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column (name="id")
     */
    protected $id;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column (name="date_creation")
     */
    protected $date_creation;

    /**
     * @var string
     * @ORM\Column (name="token")
     */
    protected $token;

    /**
     * @var bool
     * @ORM\Column (name="is_active")
     */
    protected $is_active;

    /**
     * @var User
     * @ORM\ManyToOne (targetEntity="Rbac\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn (name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return UserToken
     */
    public function setId(int $id): UserToken
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateCreation(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->date_creation);
    }

    /**
     * @param \DateTimeImmutable $date_creation
     * @return UserToken
     */
    public function setDateCreation(\DateTimeImmutable $date_creation): UserToken
    {
        $this->date_creation = $date_creation->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return UserToken
     */
    public function setToken(string $token): UserToken
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $User
     * @return UserToken
     */
    public function setUser(User $user): UserToken
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function isIsActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * @param bool $is_active
     * @return UserToken
     */
    public function setIsActive(bool $is_active): UserToken
    {
        $this->is_active = (int) $is_active;
        return $this;
    }

}