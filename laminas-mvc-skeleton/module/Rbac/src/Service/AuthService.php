<?php

namespace Rbac\Service;

use Laminas\Authentication\Adapter\AdapterInterface;
use Rbac\Adapter\UserAdapter;

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
     * @param array $config
     */
    public function __construct(array $config, AccountService $accountService, UserAdapter $adapter)
    {
        $this->config = $config;
        $this->accountService = $accountService;
        $this->adapter = $adapter;
    }

    public function checkUser(array $data)
    {
        $this->adapter
            ->setLogin($data['login'])
            ->setPassword($data['password'])
            ->authenticate();

    }

    public function authenticate(string $controllerName, string $actionName)
    {
        $mode = $this->config['mode'];
        $parameters = $this->config['parameters'];
        $controllerConfig = $parameters[$controllerName]??null;

        if(!$controllerConfig){
            if($mode == self::RESTRICTIVE){
                //no config available for this controller but mode restricive access denied
                return self::ACCESS_DENIED;
            }
            return self::ACCESS_GRANTED;
        }

        $actionConfig = $controllerConfig[$actionName]??null;
        if(!$actionConfig){
            if($mode == self::RESTRICTIVE){
                //no config available for this controller but mode restricive access denied
                return self::ACCESS_DENIED;
            }
            return self::ACCESS_GRANTED;
        }

        if($actionConfig == '*'){
            // * grants access to everyone so access granted
            return self::ACCESS_GRANTED;
        }

        return $this->checkAuth($actionConfig);
    }

    protected function checkAuth(string $actionConfig): int
    {
        //@ alone means that every connected user can log in

        return self::ACCESS_DENIED;
    }

}