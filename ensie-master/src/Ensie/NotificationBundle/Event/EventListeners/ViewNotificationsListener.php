<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-8-14
 * Time: 13:38
 */

namespace Ensie\NotificationBundle\Event\EventListeners;

use Doctrine\ORM\EntityManagerInterface;
use Ensie\NotificationBundle\Notification\NotificationService;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class ViewNotificationsListener implements EventSubscriberInterface{

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;

    /**
     * @var \Ensie\NotificationBundle\Notification\NotificationService
     */
    private $notificationService;

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    private $security_context;

    public function __construct(RouterInterface $router, SecurityContextInterface $security_context, EntityManagerInterface $em, NotificationService $notificationService)
    {
        $this->router = $router;
        $this->security_context = $security_context;
        $this->em = $em;
        $this->notificationService = $notificationService;
    }

    public function onFinishRequest(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $routename = $request->attributes->get('_route');
        if($routename == 'profile_notifications_view'){
            $user = $this->security_context->getToken()->getUser();
            $notifications = $this->notificationService->getUnviewedNotifications($user);
            $this->notificationService->viewNotifications($notifications);
            $this->em->flush();
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::RESPONSE => array(array('onFinishRequest', 17)),
        );
    }

} 