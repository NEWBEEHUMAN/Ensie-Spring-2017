<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:26
 */

namespace Ensie\MandrillMailerBundle\Event\EventListeners;

use Ensie\EnsieBundle\Event\Events\DefinitionEvents;
use Ensie\EnsieBundle\Event\Events\DefinitionValidationEvent;
use Ensie\MandrillMailerBundle\Service\MandrillMailSender;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouterInterface;

class DefinitionRejectedMailListener implements EventSubscriberInterface{

    private $mandrillMailSender;
    private $router;

    public function __construct(MandrillMailSender $mandrillMailSender, RouterInterface $router)
    {
        $this->mandrillMailSender = $mandrillMailSender;
        $this->router = $router;
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

        if($user instanceof EnsieUser)
        {
            $extraData['PROFILEDEFINITIONWRITE'] = $this->router->generate('profile_definition_write', array(), RouterInterface::ABSOLUTE_URL);
            $extraData['FEEDBACK'] = $event->getDefinitionValidation()->getFeedback();
            $this->mandrillMailSender->send($user, 'definition', 'rejected', $extraData);
        }
    }
}