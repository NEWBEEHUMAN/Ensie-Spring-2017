<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:26
 */

namespace Ensie\MailerBundle\Event\EventListeners;


use Ensie\EnsieBundle\Event\Events\SpamDefinitionEvent;
use Ensie\EnsieBundle\Event\Events\SpamEvents;
use Ensie\MailerBundle\MailParameters\ReportSpamToAdminMailParameters;
use Ensie\MailerBundle\Service\MailerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SpamDefinitionListener implements EventSubscriberInterface{

    private $mailerService;

    public function __construct(MailerService $mailerService, $mailToAdminAddress)
    {
        $this->mailerService = $mailerService;
        $this->mailToAdminAddress = $mailToAdminAddress;
    }

    public function onSpamReporting(SpamDefinitionEvent $event)
    {
        $this->mailerService->sentMail($this->mailToAdminAddress, new ReportSpamToAdminMailParameters(
            $event->getDefinition(),
            $event->getEnsieUser(),
            $event->getSpam()
        ));

    }

    public static function getSubscribedEvents()
    {
        return array(
            SpamEvents::ENSIE_DEFINITION_SPAM_REPORTED => array(array('onSpamReporting')),
        );
    }
}