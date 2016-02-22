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
use Ensie\EnsieBundle\Event\Events\DefinitionValidationEvent;
use Ensie\EnsieBundle\Event\Events\ViewDefinitionEvent;
use Ensie\EnsieBundle\Event\Events\ViewEvents;
use Ensie\MailerBundle\MailParameters\SendFriendAddedMailParameters;
use Ensie\MailerBundle\Service\MailerService;
use Ensie\NotificationBundle\Entity\NotificationTemplate;
use Ensie\NotificationBundle\Notification\NotificationService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\Friend;
use Ensie\UserBundle\Entity\FriendRepository;
use Ensie\UserBundle\Event\Events\UserEvents;
use Ensie\UserBundle\Event\Events\UserFriendEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DefinitionCreatedNotificationListener implements EventSubscriberInterface{

    /** @var NotificationService $notificationService */
    private $notificationService;
    /** @var \Doctrine\ORM\EntityManagerInterface  */
    private $entityManager;
    /** @var \Ensie\UserBundle\Entity\FriendRepository */
    private $friendRepository;

    public function __construct(EntityManagerInterface $entityManager, NotificationService $notificationService, FriendRepository $friendRepository)
    {
        $this->notificationService = $notificationService;
        $this->entityManager = $entityManager;
        $this->friendRepository = $friendRepository;
    }

    public static function getSubscribedEvents()
    {
        return array(
            DefinitionEvents::DEFINITION_CREATED => array(array('addNotification')),
        );
    }

    public function addNotification(DefinitionEvent $event)
    {
        $definition = $event->getDefinition();
        $friends = $this->friendRepository->getAllFavoritesByUser($definition->getEnsieUser());
        /** @var $friend Friend */
        foreach ($friends as $friend) {
            $this->notificationService->createNotification(
                $friend->getEnsieUser(),
                NotificationTemplate::IDENTIFIER_DEFINITION_ADDED_BY_FAVORITE,
                array('definition' => $definition),
                $definition->getEnsieUser()
            );
        }
        $this->entityManager->flush();
    }
}