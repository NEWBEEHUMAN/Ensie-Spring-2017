<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:26
 */

namespace Ensie\MailerBundle\Event\EventListeners;

use Ensie\EnsieBundle\Event\Events\DefinitionEvents;
use Ensie\EnsieBundle\Event\Events\DefinitionValidationEvent;
use Ensie\MailerBundle\MailParameters\DefinitionRejectedMailParameters;
use Ensie\MailerBundle\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DefinitionRejectedMailListener implements EventSubscriberInterface{

    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    public static function getSubscribedEvents()
    {
        return array(
            DefinitionEvents::DEFINITION_REJECTED => array(array('definitionRejected')),
        );
    }

    public function definitionRejected(DefinitionValidationEvent $event)
    {
        $user = $event->getDefinitionValidation()->getDefinition()->getEnsieUser();
        $this->mailerService->sentMail(
            $user->getEmail(),
            new DefinitionRejectedMailParameters($event->getDefinitionValidation()->getDefinition(), $event->getDefinitionValidation()->getFeedback()),
            $user->getLanguage()->getLocale()
        );
    }
}