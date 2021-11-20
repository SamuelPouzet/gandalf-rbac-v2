<?php

namespace Rbac\Service;

use Rbac\Adapter\UserAdapter;
use Rbac\Manager\BanManager;
use Laminas\Http\PhpEnvironment\RemoteAddress;

class FailToBanService
{

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $login;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var BanManager
     */
    protected $banManager;

    /**
     * @var int
     */
    protected $tries = 0;


    public function __construct(BanManager $banManager, array $config)
    {
        $this->banManager = $banManager;
        $this->config = $config;
    }

    public function failLogin(UserAdapter $adapter): void
    {
        $remote  = new RemoteAddress();
        $ip = ip2long($remote->getIpAddress());

    }



}