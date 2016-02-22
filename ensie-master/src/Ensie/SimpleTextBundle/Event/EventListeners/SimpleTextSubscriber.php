<?php
/**
 * Created by PhpStorm.
 * User: vladyslav
 * Date: 13.03.15
 * Time: 21:46
 */

namespace Ensie\SimpleTextBundle\Event\EventListeners;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Ensie\SimpleTextBundle\Entity\SimpleText;
use Ensie\SimpleTextBundle\Entity\SimpleTextTranslation;

class SimpleTextSubscriber implements EventSubscriber{

    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * @param CacheProvider $cache
     */
    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    public function getSubscribedEvents()
    {
        die('a');
        return array(
            'postPersist',
            'postUpdate',
        );
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postPersist(LifecycleEventArgs $event){
        $entity = $event->getEntity();
        if ($entity instanceof SimpleText) {
            var_dump("post persist: simpleTExt");
            $this->cache->save($entity->getIdentifier(),$entity);
        }
        if ($entity instanceof SimpleTextTranslation) {
            var_dump("post persist: simpleTExtTranslation");
            $identifier = $entity->getTranslatable()->getIdentifier();
            $this->cache->delete($identifier);
            $this->cache->save($identifier, $entity->getTranslatable());
        }
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event){
        die('b');
        $entity = $event->getEntity();
        if ($entity instanceof SimpleText) {
            var_dump("post update:SimpleTexxt");
            $this->cache->delete($entity->getIdentifier());
            $this->cache->save($entity->getIdentifier(),$entity);
        }

        if ($entity instanceof SimpleTextTranslation) {
            var_dump("post update:SimpleTexxtTranslation");
            $identifier = $entity->getTranslatable()->getIdentifier();
            $this->cache->delete($identifier);
            $this->cache->save($identifier, $entity->getTranslatable());
        }

    }

}