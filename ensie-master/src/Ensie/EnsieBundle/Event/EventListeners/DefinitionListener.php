<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 15-8-14
 * Time: 13:33
 */

namespace Ensie\EnsieBundle\Event\EventListeners;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Event\Events\DefinitionEvent;
use Ensie\EnsieBundle\Event\Events\DefinitionEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DefinitionListener implements EventSubscriber{

    /** @var  EventDispatcherInterface */
    private $eventDispatcher;

    function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getSubscribedEvents()
    {
        return array(
            "prePersist",
            "postPersist",
            "preUpdate",
            "preRemove"
        );
    }

    public function prePersist(LifecycleEventArgs $event){
        if($definition = $this->getDefinition($event)){
            $this->activateDefinition($definition);
        }
    }

    public function postPersist(LifecycleEventArgs $event){
        if($definition = $this->getDefinition($event)){
            $this->validateDefinition($definition);
            $this->eventDispatcher->dispatch(DefinitionEvents::DEFINITION_CREATED, new DefinitionEvent($definition));
        }
    }

    public function preUpdate(LifecycleEventArgs $event){
        if($definition = $this->getDefinition($event)){
            $this->validateDefinition($definition);
            $this->activateDefinition($definition);
        }
    }

    public function preRemove(LifecycleEventArgs $event){
        if($definition = $this->getDefinition($event)){
            $this->eventDispatcher->dispatch(DefinitionEvents::DEFINITION_REMOVED, new DefinitionEvent($definition));
        }
    }

    private function validateDefinition(Definition $definition){
        if(!$definition->getEnsieUser()->getEnabledWriter() or ($definition->getEnsieUser()->isCompany() and !$definition->getValidated())){
            $this->eventDispatcher->dispatch(DefinitionEvents::DEFINITION_VALIDATE, new DefinitionEvent($definition));
        }
    }

    private function activateDefinition(Definition $definition){
        /* set validated on true if it is not a company */
        if(!$definition->getEnsieUser()->isCompany()){
            $definition->setValidated(true);
        }
    }

    /**
     * @param LifecycleEventArgs $event
     * @return bool|Definition
     */
    private function getDefinition(LifecycleEventArgs $event){
        /** @var Definition $ensieUser */
        $definition = $event->getEntity();
        if($definition instanceof Definition){
            return $definition;
        }
        return false;
    }
} 