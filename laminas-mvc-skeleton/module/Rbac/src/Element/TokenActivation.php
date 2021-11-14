<?php

namespace Rbac\Element;

use Rbac\Entity\UserToken;

/**
 *
 */
class TokenActivation
{

    /**
     * @const int NO_TOKEN_AVAILABLE
     */
    const NO_TOKEN_AVAILABLE = 0;

    /**
     * @const int ALREADY_ACTIVATED
     */
    const ALREADY_ACTIVATED = 1;

    /**
     * @const int TOKEN_AVAILABLE
     */
    const TOKEN_AVAILABLE = 2;

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var UserToken|null
     */
    protected $token;

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @param int $code
     * @return TokenActivation
     */
    public function setCode(int $code): TokenActivation
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
     * @return TokenActivation
     */
    public function setMessage(string $message): TokenActivation
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return UserToken|null
     */
    public function getToken(): ?UserToken
    {
        return $this->token;
    }

    /**
     * @param UserToken|null $token
     * @return TokenActivation
     */
    public function setToken(?UserToken $token): TokenActivation
    {
        $this->token = $token;
        return $this;
    }

}