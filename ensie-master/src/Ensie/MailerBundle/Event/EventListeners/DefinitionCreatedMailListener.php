<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:26
 */

namespace Ensie\MailerBundle\Event\EventListeners;

use Ensie\EnsieBundle\Event\Events\DefinitionEvent;
use Ensie\EnsieBundle\Event\Events\DefinitionEvents;
use Ensie\MailerBundle\MailParameters\DefinitionCreatedAdminMailParameters;
use Ensie\MailerBundle\MailParameters\DefinitionCreatedMailParameters;
use Ensie\MailerBundle\Service\MailerService;
use Ensie\UserBundle\Entity\Friend;
use Ensie\UserBundle\Entity\FriendRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DefinitionCreatedMailListener implements EventSubscriberInterface
{

    private $mailerService;
    /** @var \Ensie\UserBundle\Entity\FriendRepository */
    private $friendRepository;
    private $mailToAdminAddress;

    public function __construct(MailerService $mailerService, FriendRepository $friendRepository, $mailToAdminAddress)
    {
        $this->mailerService = $mailerService;
        $this->friendRepository = $friendRepository;
        $this->mailToAdminAddress = $mailToAdminAddress;
    }

    public static function getSubscribedEvents()
    {
        return array(
            DefinitionEvents::DEFINITION_CREATED => array(array('definitionCreated')),
        );
    }

    public function definitionCreated(DefinitionEvent $event)
    {
        $user = $event->getDefinition()->getEnsieUser();
        //Mail to admin
        $this->mailerService->sentMail(
            $this->mailToAdminAddress,
            new DefinitionCreatedAdminMailParameters($user, $event->getDefinition()),
            $user->getLanguage()->getLocale()
        );
    }
}