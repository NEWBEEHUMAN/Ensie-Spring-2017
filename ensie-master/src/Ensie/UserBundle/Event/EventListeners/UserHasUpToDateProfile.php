<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 14-8-14
 * Time: 19:22
 */

namespace Ensie\UserBundle\Event\EventListeners;


use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UserHasUpToDateProfile implements EventSubscriberInterface{

    private $security_context;
    private $router;
    private $session;

    public function __construct(RouterInterface $router, SecurityContextInterface $security_context, Session $session)
    {
        $this->security_context = $security_context;
        $this->router = $router;
        $this->session = $session;
    }

    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::REQUEST => array(array('hasUpToDateProfile')),
        );
    }

    public function hasUpToDateProfile(GetResponseEvent $event){
        $token = $this->security_context->getToken();
        if ($token
            && $this->security_context->isGranted('IS_AUTHENTICATED_FULLY')
            && $this->security_context->isGranted('ROLE_ENSIE_USER')
            && !$this->security_context->isGranted('ROLE_ADMIN')) {

//            if(!in_array($event->getRequest()->getRequestUri(), $allowedRoutes)){
//                /** @var EnsieUser $user */
//                $user = $token->getUser();
//                if($user->getFormattedname()){
//                    die('event triggerd');
//                    //$event->setResponse(new RedirectResponse($this->router->generate('admin_zupr_merchant_merchant_create')));
//                }
//            }
        }
    }
} 