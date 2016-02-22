<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 14-8-14
 * Time: 11:54
 */

namespace Ensie\MandrillMailerBundle\Event\EventListeners;

use Ensie\MandrillMailerBundle\Service\MandrillMailSender;
use Ensie\UserBundle\Entity\EnsieUser;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SendWelcomeMailListener implements EventSubscriberInterface{

    private $mandrillMailSender;

    public function __construct(MandrillMailSender $mandrillMailSender)
    {
        $this->mandrillMailSender = $mandrillMailSender;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => "onRegistrationSuccess",
        );
    }

    public function onRegistrationSuccess(FilterUserResponseEvent $event){
        $user = $event->getUser();
        if($user instanceof EnsieUser)
        {
            /** @var EnsieUser $user */
            if(!$user->isCompany()) {
                $this->mandrillMailSender->send($user, 'registration', 'register');
            }
        }
    }


}