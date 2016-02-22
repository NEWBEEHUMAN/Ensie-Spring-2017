<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:26
 */

namespace Ensie\RedirectBundle\Event\EventListeners;

use Ensie\EnsieBundle\Event\Events\DefinitionEvents;
use Ensie\EnsieBundle\Event\Events\DefinitionValidationEvent;
use Ensie\MailerBundle\MailParameters\DefinitionAcceptedMailParameters;
use Ensie\MailerBundle\Service\MailerService;
use Ensie\RedirectBundle\Controller\RedirectController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class RedirectListener{


    /** @var \Symfony\Component\Routing\RouterInterface  */
    private $router;
    /** @var \Symfony\Component\DependencyInjection\Container  */
    private $container;

    public function __construct(RouterInterface $router, Container $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        /** @var RedirectController $redirectController */
        $redirectController = $this->container->get('redirect_controller');
        if($redirectResponse = $redirectController->getRedirectResponse($event->getRequest()))
        {
            $event->setResponse($redirectResponse);
        }
    }
}