<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:26
 */

namespace Ensie\MailerBundle\Event\EventListeners;


use Doctrine\ORM\EntityManager;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Entity\ViewRepository;
use Ensie\EnsieBundle\Event\Events\ViewDefinitionEvent;
use Ensie\EnsieBundle\Event\Events\ViewEvents;
use Ensie\MailerBundle\MailParameters\SendFriendAddedMailParameters;
use Ensie\MailerBundle\Service\MailerService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Event\Events\UserEvents;
use Ensie\UserBundle\Event\Events\UserFriendEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FriendAddedMailListener implements EventSubscriberInterface{

    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::USER_FRIEND_ADDED => array(array('sendFriendAddedMail')),
        );
    }

    public function sendFriendAddedMail(UserFriendEvent $event)
    {
        // 14-11-28 Removed we keep it in case
//        if($event->getFriend()->getFriend()->getReceiveEmails()){
//            $this->mailerService->sentMail(
//                $event->getFriend()->getFriend()->getEmail(),
//                new SendFriendAddedMailParameters($event->getFriend()->getEnsieUser(), $event->getFriend()->getFriend()),
//                $event->getFriend()->getFriend()->getLanguage()->getLocale()
//            );
//        }
    }
}