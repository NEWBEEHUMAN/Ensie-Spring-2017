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
use Ensie\MailerBundle\MailParameters\DefinitionValidationMailParameters;
use Ensie\MailerBundle\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DefinitionValidationMailListener implements EventSubscriberInterface{

    private $mailerService;

    private $mailToAdminAddress;

    public function __construct(MailerService $mailerService, $mailToAdminAddress)
    {
        $this->mailerService = $mailerService;
        $this->mailToAdminAddress = $mailToAdminAddress;
    }

    public static function getSubscribedEvents()
    {
        return array(
            DefinitionEvents::DEFINITION_VALIDATE => array(array('sendValidationMail')),
        );
    }

    public function sendValidationMail(DefinitionEvent $event)
    {
        $this->mailerService->sentMail(
            $this->mailToAdminAddress,
            new DefinitionValidationMailParameters($event->getDefinition())
        );
    }
}