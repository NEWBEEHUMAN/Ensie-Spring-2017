<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:26
 */

namespace Ensie\NotificationBundle\Event\EventListeners;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Entity\ViewRepository;
use Ensie\EnsieBundle\Event\Events\ViewDefinitionEvent;
use Ensie\EnsieBundle\Event\Events\ViewEvents;
use Ensie\MailerBundle\MailParameters\SendFriendAddedMailParameters;
use Ensie\MailerBundle\Service\MailerService;
use Ensie\NotificationBundle\Entity\NotificationTemplate;
use Ensie\NotificationBundle\Notification\NotificationService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Event\Events\UserEvents;
use Ensie\UserBundle\Event\Events\UserFriendEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FriendAddedNotificationListener implements EventSubscriberInterface{

    /** @var NotificationService $notificationService */
    private $notificationService;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            UserEvents::USER_FRIEND_ADDED => array(array('addNotification')),
        );
    }

    public function addNotification(UserFriendEvent $event)
    {
        $event->getFriend()->getFriend();
        $this->notificationService->createNotification($event->getFriend()->getFriend(), NotificationTemplate::IDENTIFIER_FAVORITE, array('ensieUser' => $event->getFriend()->getEnsieUser()), $event->getFriend()->getEnsieUser());
        //$this->notificationService->createNotification($event->getFriend()->getEnsieUser(), NotificationTemplate::IDENTIFIER_FAVORITE, array('ensieUser' => $event->getFriend()->getFriend()), $event->getFriend()->getFriend());
        $this->entityManager->flush();
    }
}