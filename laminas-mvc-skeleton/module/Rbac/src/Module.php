<?php
declare(strict_types=1);

namespace Rbac;

use Laminas\Mvc\MvcEvent;
use Laminas\Session\SessionManager;
use Rbac\Listener\AuthListener;

class Module
{
    public function getConfig(): array
    {
        /** @var array $config */
        $config = include __DIR__ . '/../config/module.config.php';
        return $config;
    }

    public function onBootstrap(MvcEvent $event) : void
    {
        $application = $event->getApplication();
        $eventManager = $application->getEventManager();

        $serviceManager = $application->getServiceManager();

        // The following line instantiates the SessionManager and automatically
        // makes the SessionManager the 'default' one to avoid passing the
        // session manager as a dependency to other models.
        $sessionManager = $serviceManager->get(SessionManager::class);

        $listener = new AuthListener($event);
        $listener->attach($eventManager);


    }
}