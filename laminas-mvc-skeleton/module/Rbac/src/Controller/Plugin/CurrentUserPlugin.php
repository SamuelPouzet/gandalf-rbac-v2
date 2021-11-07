<?php

namespace Rbac\Controller\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Rbac\Entity\User;
use Rbac\Service\AccountService;

class CurrentUserPlugin extends AbstractPlugin
{

    /**
     * @var AccountService $accountservice
     */
    protected $accountService;

    /**
     * @var User|null $currentUser
     */
    protected $currentUser = null;

    /**
     * @param AccountService $accountservice
     */
    public function __construct(AccountService $accountservice)
    {
        $this->accountservice = $accountservice;
    }

    public function __invoke()
    {

        if($this->currentUser){
            return $this->currentUser;
        }

        $this->currentUser = $this->accountservice->getInstance();
        return $this->currentUser;
    }


}