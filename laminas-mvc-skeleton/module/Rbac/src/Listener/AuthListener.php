<?php

namespace Rbac\Listener;

use Laminas\EventManager\EventManagerInterface;
use Laminas\Mvc\MvcEvent;

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
        $actionName = $routeMatch->getParam('action', null);

        var_dump($actionName);die;
    }
}