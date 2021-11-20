<?php

namespace Rbac\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_failed_tries")
 */
class FailedTries
{

    const TYPE_IP = 0;
    const TYPE_Login = 1;

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column (name="id")
     */
    protected $id;

    /**
     * @var int
     * @ORM\Column (name="identifier_type")
     */
    protected $type;

    /**
     * @var string
     * @ORM\Column (name="identifier")
     */
    protected $identifier;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column (name="last_try")
     */
    protected $last_try;

    /**
     * @var int
     * @ORM\Column (name="tries")
     */
    protected $tries;

    public function __construct()
    {

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
     * @return FailedTries
     */
    public function setId(int $id): FailedTries
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @param int $type
     * @return FailedTries
     */
    public function setType(int $type): FailedTries
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     * @return FailedTries
     */
    public function setIdentifier(string $identifier): FailedTries
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getLastTry(): \DateTimeImmutable
    {
        return new \DateTimeImmutable($this->last_try);
    }

    /**
     * @param \DateTimeImmutable $last_try
     * @return FailedTries
     */
    public function setLastTry(\DateTimeImmutable $last_try): FailedTries
    {
        $this->last_try = $last_try->format('Y-m-d H:i:s');
        return $this;
    }

    /**
     * @return int
     */
    public function getTries(): int
    {
        return $this->tries;
    }

    /**
     * @param int $tries
     * @return FailedTries
     */
    public function setTries(int $tries): FailedTries
    {
        $this->tries = $tries;
        return $this;
    }

}