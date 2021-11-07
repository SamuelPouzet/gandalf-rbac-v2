<?php

namespace Rbac\Service;

use Laminas\Authentication\Storage\Session;
use Laminas\Session\SessionManager;

/**
 *
 */
class SessionService
{
    /**
     * @var Session
     */
    protected $session;

    /**
     * @var SessionManager
     */
    protected $sessionManager;

    /**
     * @param Session $session
     * @param SessionManager $sessionManager
     */
    public function __construct(Session $session, SessionManager $sessionManager)
    {
        $this->session = $session;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param string $content
     */
    public function write(string $content): void
    {
        $this->session->write($content);
    }

    /**
     *
     */
    public function clear(): void
    {
        $this->session->clear();
    }

    /**
     * @return string
     */
    public function read(): string
    {
        return $this->session->read();
    }

    /**
     * @param int|null $ttl
     */
    public function rememberMe(?int $ttl = null): void
    {
        $this->sessionManager->rememberMe($ttl);
    }

    public function isEmpty(): bool
    {
        return $this->session->isEmpty();
    }


}