<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 10-9-14
 * Time: 20:58
 */

namespace Ensie\EnsieBundle\Event\EventListeners;


use Ensie\EnsieBundle\Entity\EnsieRepository;
use Ensie\EnsieBundle\Form\Datatransformer\EnsieToTitleTransformer;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;

class DynamicFormListener implements EventSubscriberInterface
{
    private $factory;
    private $ensieUser;
    private $container;

    public function __construct(FormFactoryInterface $factory, EnsieUser $ensieUser, Container $container)
    {
        $this->factory = $factory;
        $this->ensieUser = $ensieUser;
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SUBMIT   => 'preSubmit',
            FormEvents::PRE_SET_DATA => 'preSetData',
        );
    }
    public function preSetData(FormEvent $event)
    {
        // Don't need
        return;
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $ensieText = $data['ensieText'];
        $form = $event->getForm();
        $ensieRepository  = $this->container->get();
        if (!$ensieText){
            $form->add('ensieText', 'text', array(
                'property_path' => 'ensie',
                'mapped' => true
            ))->addModelTransformer(new EnsieToTitleTransformer($ensieRepository, $this->ensieUser));
        } return; // If nothing was actually chosen

        return;
    }
}