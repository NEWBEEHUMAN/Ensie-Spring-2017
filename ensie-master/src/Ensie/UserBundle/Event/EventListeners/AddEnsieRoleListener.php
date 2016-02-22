<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 14-8-14
 * Time: 11:54
 */

namespace Ensie\UserBundle\Event\EventListeners;

use Doctrine\ORM\EntityManagerInterface;
use Ensie\UserBundle\Entity\EnsieUser;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Zupr\UserBundle\Entity\User;
//use Zupr\UserBundle\Event\Events\UserEvent;

class AddEnsieRoleListener implements EventSubscriberInterface{

    private $em;
    private $role;

    public function __construct(EntityManagerInterface $em, $role)
    {
        $this->em = $em;
        $this->role = $role;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => "onRegistrationSuccess",
        );
    }

    public function onRegistrationSuccess(FilterUserResponseEvent $event){
        /** @var EnsieUser $user */
        $user = $event->getUser();
        $user->addRole($this->role);
        $this->em->flush();
    }


}