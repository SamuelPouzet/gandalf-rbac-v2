<?php

namespace Rbac\Element;

use Rbac\Entity\User;

class Result
{
    /**
     * Failure due to configuration mistake
     */
    const CONF_ERROR = 1;

    /**
     * Failure due to identity being ambiguous.
     */
    const FAILURE_IDENTITY_AMBIGUOUS = 2;

    /**
     * Failure due to user not found.
     */
    const USER_NOT_FOUND = 3;

    /**
     * Allright, access granted
     */
    const ACCESS_GRANTED = 4;

    /**
     * password rejected
     */
    const PASSWORD_REJECTED = 5;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var User|null
     */
    protected $user;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return Result
     */
    public function setCode(int $code): Result
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return Result
     */
    public function setMessage(string $message): Result
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     * @return Result
     */
    public function setUser(?User $user): Result
    {
        $this->user = $user;
        return $this;
    }

}