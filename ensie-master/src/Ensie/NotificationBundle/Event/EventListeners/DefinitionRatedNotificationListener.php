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
use Ensie\EnsieBundle\Event\Events\DefinitionEvent;
use Ensie\EnsieBundle\Event\Events\DefinitionEvents;
use Ensie\EnsieBundle\Event\Events\DefinitionFeedbackEvent;
use Ensie\EnsieBundle\Event\Events\DefinitionRatedEvent;
use Ensie\EnsieBundle\Event\Events\DefinitionValidationEvent;
use Ensie\EnsieBundle\Event\Events\ViewDefinitionEvent;
use Ensie\EnsieBundle\Event\Events\ViewEvents;
use Ensie\MailerBundle\MailParameters\SendFriendAddedMailParameters;
use Ensie\MailerBundle\Service\MailerService;
use Ensie\NotificationBundle\Entity\NotificationTemplate;
use Ensie\NotificationBundle\Notification\NotificationService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\FriendRepository;
use Ensie\UserBundle\Event\Events\UserEvents;
use Ensie\UserBundle\Event\Events\UserFriendEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DefinitionRatedNotificationListener implements EventSubscriberInterface{

    /** @var NotificationService $notificationService */
    private $notificationService;
    /** @var \Doctrine\ORM\EntityManagerInterface  */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            DefinitionEvents::DEFINITION_RATED => array(array('addRatedNotification')),
            DefinitionEvents::DEFINITION_RATED_FEEDBACK => array(array('addRatedFeedbackNotification')),
        );
    }

    public function addRatedNotification(DefinitionRatedEvent $event)
    {
        $this->notificationService->createNotification(
            $event->getRating()->getDefinition()->getEnsieUser(),
            NotificationTemplate::IDENTIFIER_RATED,
            array('rating' => $event->getRating()),
            $event->getRating()->getUser()
        );
        $this->entityManager->flush();
    }

    public function addRatedFeedbackNotification(DefinitionRatedEvent $event)
    {
        $this->notificationService->createNotification(
            $event->getRating()->getDefinition()->getEnsieUser(),
            NotificationTemplate::IDENTIFIER_RATED_FEEDBACK,
            array('rating' => $event->getRating()),
            $event->getRating()->getUser()
        );
        $this->entityManager->flush();
    }
}