<?php

namespace Rbac\Service;

use Laminas\Authentication\Storage\Session;
use Rbac\Adapter\UserAdapter;
use Rbac\Element\Result;

class AuthService
{

    const RESTRICTIVE = 'restrictive';
    const NEED_CONNECTION = 0;
    const ACCESS_GRANTED = 1;
    const ACCESS_DENIED = 2;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var AccountService
     */
    protected $accountService;

    /**
     * @var UserAdapter
     */
    protected $adapter;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param array $config
     */
    public function __construct(array $config, AccountService $accountService, UserAdapter $adapter, Session $session)
    {
        $this->config = $config;
        $this->accountService = $accountService;
        $this->adapter = $adapter;
        $this->session = $session;
    }

    public function checkUser(array $data)
    {
        if ($this->accountService->hasIdentity()) {
            die('Already logged in');
        }

        $result = $this->adapter
            ->setLogin($data['email'])
            ->setPassword($data['password'])
            ->authenticate();

        if ($result->getCode() == Result::ACCESS_GRANTED) {
            $this->session->write($result->getUser()->getId());
            die('access granted, need to create session');
        }
    }

    public function authenticate(string $controllerName, string $actionName)
    {
        $mode = $this->config['mode'];
        $parameters = $this->config['parameters'];
        $controllerConfig = $parameters[$controllerName] ?? null;

        if (!$controllerConfig) {
            if ($mode == self::RESTRICTIVE) {
                //no config available for this controller but mode restricive access denied
                return self::ACCESS_DENIED;
            }
            return self::ACCESS_GRANTED;
        }

        $actionConfig = $controllerConfig[$actionName] ?? null;
        if (!$actionConfig) {
            if ($mode == self::RESTRICTIVE) {
                //no config available for this controller but mode restricive access denied
                return self::ACCESS_DENIED;
            }
            return self::ACCESS_GRANTED;
        }

        if ($actionConfig == '*') {
            // * grants access to everyone so access granted
            return self::ACCESS_GRANTED;
        }
        if (!is_array($actionConfig)) {
            return $this->checkAuth($actionConfig);
        } else {
            return $this->parseAuth($actionConfig);
        }

    }

    protected function checkAuth(string $actionConfig): int
    {
        //@ alone means that every connected user can log in
        if ($actionConfig == '@') {
            if ($this->accountService->hasIdentity()) {
                return self::ACCESS_GRANTED;
            }
            return self::NEED_CONNECTION;
        }

        return self::ACCESS_DENIED;
    }

    protected function parseAuth(array $config): int
    {
        $identity = $this->accountService->getInstance();
        $login = strtolower($identity->getLogin());

        foreach ($config as $c) {
            $identifier = $c[0];
            if ($identifier == '@') {
                if ('@' . $login == strtolower($c)) {
                    return self::ACCESS_GRANTED;
                }
            }
        }
        return self::ACCESS_DENIED;
    }

}