<?php

namespace Rbac\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;
use Rbac\Controller\LogController;
use Rbac\Service\AuthService;


/**
 *
 */
class AuthListener
{
    /**
     * @var MvcEvent
     */
    protected $event;

    /**
     * @param MvcEvent $event
     */
    public function __construct(MvcEvent $event)
    {
        $this->event = $event;
    }

    /**
     * @param EventManagerInterface $events
     * @param int $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        $this->listeners[] = $events->attach(
            MvcEvent::EVENT_DISPATCH,
            [$this, 'checkAuth']
        );
    }

    public function checkAuth()
    {
        $routeMatch = $this->event->getRouteMatch();
        $controllerName = $routeMatch->getParam('controller', null);

        if($controllerName == LogController::class){
            //never check the logcontroller to avoid loop redirections
            return true;
        }
        $actionName = $routeMatch->getParam('action', null);
        $actionName = str_replace('-', '', lcfirst(ucwords($actionName, '-')));
        $authService = $this->event->getApplication()->getServiceManager()->get(AuthService::class);

        $response = $authService->authenticate($controllerName, $actionName);

        switch ($response) {
            case AuthService::ACCESS_DENIED:
                //redirect to access denied page
                return $this->event->getTarget()->redirect()->toRoute('forbidden');
            case AuthService::NEED_CONNECTION:
                return $this->event->getTarget()->redirect()->toRoute('login');
        }
    }
}