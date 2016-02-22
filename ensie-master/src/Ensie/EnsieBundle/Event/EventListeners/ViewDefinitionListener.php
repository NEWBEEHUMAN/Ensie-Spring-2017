<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:26
 */

namespace Ensie\EnsieBundle\Event\EventListeners;


use Doctrine\ORM\EntityManager;
use Ensie\EnsieBundle\Entity\ViewRepository;
use Ensie\EnsieBundle\Event\Events\ViewDefinitionEvent;
use Ensie\EnsieBundle\Event\Events\ViewEvents;
use Ensie\EnsieBundle\Service\DefinitionService;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ViewDefinitionListener implements EventSubscriberInterface{

    private $viewRepository;
    private $em;

    public function __construct(ViewRepository $viewRepository, DefinitionService $definitionService, EntityManager $em)
    {
        $this->viewRepository = $viewRepository;
        $this->definitionService = $definitionService;
        $this->em = $em;
    }

    public function onEnsieDefinitionView(ViewDefinitionEvent $event)
    {
        //Todo more security and make it more hack proof
        //Add view and update definition
        $definition = $event->getDefinition();
        $user = $event->getEnsieUser();
        if($user instanceof EnsieUser){
            if($user === $definition->getEnsieUser()){
                return; //Don't view your own definition
            }
        } else {
            $user = null;
        }
        $this->viewRepository->create($definition, $user);
        $this->em->flush();
        if(rand(0,20) == 0) {
            //Update the defintion one on 20 views, maybe this will stop the Deadlock
            $this->definitionService->updateStatsDefinition($definition);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            ViewEvents::ENSIE_DEFINITION_VIEW => array(array('onEnsieDefinitionView')),
        );
    }
}