<?php

namespace Ensie\AdminBundle\Controller;

use Ensie\AdminBundle\Form\DefinitionValidationType;
use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Event\Events\DefinitionEvents;
use Ensie\EnsieBundle\Event\Events\DefinitionValidationEvent;
use Ensie\EnsieBundle\Model\DefinitionValidation;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class AdminController extends Controller
{
    /**
     * @Template()
     */
    public function validateDefinitionAction(Request $request, $id)
    {
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->get('ensie.ensie_definition_repository');
        /** @var Definition $definition */
        $definition = $definitionRepository->find($id);
        $form = $this->createForm(new DefinitionValidationType($definition), new DefinitionValidation());
        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var DefinitionValidation $definitionValidation */
            $definitionValidation = $form->getData();
            $definitionValidation->setDefinition($definition);
            /** @var EventDispatcherInterface $eventDispatcher */
            $eventDispatcher = $this->get('event_dispatcher');
            if ($form->get('reject')->isClicked()) {
                /** @var FlashBag $flashBag */
                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->add('notice', 'definition.validation_rejected_sent');
                $eventDispatcher->dispatch(DefinitionEvents::DEFINITION_REJECTED, new DefinitionValidationEvent($definitionValidation));
            }elseif($form->get('accept')->isClicked()){
                /** @var FlashBag $flashBag */
                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->add('notice', 'definition.validation_accept_sent');
                $user = $definition->getEnsieUser();
                if($user->getEnabledWriter() == 0) {
                    $eventDispatcher->dispatch(DefinitionEvents::DEFINITION_ACCEPTED, new DefinitionValidationEvent($definitionValidation));
                }
                $user->setEnabledWriter(1);
                $definition->setValidated(true); //We need to do is here for a company definition
                $this->getDoctrine()->getManager()->flush();
            }else{
                die('something went wrong');
            }
            return $this->forward('EnsieAdminBundle:Admin:validateDefinitionSend', array('id' => $id));
        }
        return array('definition' => $definition, 'form' => $form->createView());
    }

    /**
     * @Template()
     */
    public function validateDefinitionSendAction($id)
    {
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->get('ensie.ensie_definition_repository');
        $definition = $definitionRepository->find($id);
        return array('definition' => $definition);
    }
}
