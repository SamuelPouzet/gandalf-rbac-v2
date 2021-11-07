<?php

namespace Rbac\Service;

use Laminas\Authentication\Storage\Session;
use Rbac\Adapter\UserAdapter;
use Rbac\Element\Result;

/**
 * AuthService
 */
class AuthService
{

    /**
     *
     */
    const RESTRICTIVE = 'restrictive';

    /**
     *
     */
    const NEED_CONNECTION = 0;

    /**
     *
     */
    const ACCESS_GRANTED = 1;

    /**
     *
     */
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
     * @var RoleService
     */
    protected $roleService;

    /**
     * @var UserAdapter
     */
    protected $adapter;

    /**
     * @var SessionService
     */
    protected $session;

    /**
     * @param array $config
     */
    public function __construct(array $config, AccountService $accountService, RoleService $roleService, UserAdapter $adapter, SessionService $session)
    {
        $this->config = $config;
        $this->accountService = $accountService;
        $this->roleService = $roleService;
        $this->adapter = $adapter;
        $this->session = $session;
    }

    /**
     * @param array $data
     */
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
            if($data['remember_me']){
                $this->session->rememberMe();
            }
        }

        //@todo redirectroute
    }

    /**
     * quitUser
     */
    public function quitUser()
    {
        if (!$this->accountService->hasIdentity()) {
            die('Not logged in');
        }
        $this->session->clear();

    }

    /**
     * @param string $controllerName
     * @param string $actionName
     * @return int
     */
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

    /**
     * @param string $actionConfig
     * @return int
     * @throws \Laminas\Cache\Exception\ExceptionInterface
     */
    protected function checkAuth(string $actionConfig): int
    {
        if (! $this->accountService->hasIdentity()) {

            return self::NEED_CONNECTION;
        }

        //@ alone means that every connected user can log in
        if ($actionConfig == '@') {
            return self::ACCESS_GRANTED;
        }

        $identity = $this->accountService->getInstance();
        if(!$identity){
            return self::NEED_CONNECTION;
        }
        $login = strtolower($identity->getLogin());

        $identifier = $actionConfig[0];
        if ($identifier == '@') {
            if ('@' . $login == strtolower($actionConfig)) {
                return self::ACCESS_GRANTED;
            }
        } elseif ($identifier == '#') {
            //check by role
            $role = substr($actionConfig, 1);
            if ($this->roleService->userHasRole($identity, $role)) {
                return self::ACCESS_GRANTED;
            }
        } elseif ($identifier == '+') {
            //check by permission
            $permission = substr($actionConfig, 1);
            if ($this->roleService->userHasPermission($identity, $permission)) {
                return self::ACCESS_GRANTED;
            }
        }

        return self::ACCESS_DENIED;
    }

    /**
     * @param array $config
     * @return int
     * @throws \Laminas\Cache\Exception\ExceptionInterface
     */
    protected function parseAuth(array $config): int
    {
        $identity = $this->accountService->getInstance();
        if (!$identity) {
            return self::NEED_CONNECTION;
        }

        foreach ($config as $c) {
            $identifier = $c[0];
            if ($identifier == '@') {
                //check by named login
                $login = strtolower($identity->getLogin());
                if ('@' . $login == strtolower($c)) {
                    return self::ACCESS_GRANTED;
                }
            } elseif ($identifier == '#') {
                //check by role
                $role = substr($c, 1);
                if ($this->roleService->userHasRole($identity, $role)) {
                    return self::ACCESS_GRANTED;
                }
            } elseif ($identifier == '+') {
                //check by permission
                $permission = substr($c, 1);
                if ($this->roleService->userHasPermission($identity, $permission)) {
                    return self::ACCESS_GRANTED;
                }
            }
        }
        return self::ACCESS_DENIED;
    }

}