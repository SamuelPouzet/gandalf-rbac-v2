<?php

namespace Rbac\Service\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Resolver\TemplateMapResolver;
use Rbac\Service\MailerService;

class MailerServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): MailerService
    {
        $config = $container->get('config');
        if (!isset($config['mailer'])) {
            throw new \Exception('mailer parameters not found');
        }

        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');
        $resolver = $container->get(TemplateMapResolver::class);

        return new MailerService($config['mailer'], $urlHelper, $resolver);
    }

}