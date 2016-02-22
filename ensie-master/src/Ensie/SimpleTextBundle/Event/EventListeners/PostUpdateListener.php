<?php
/**
 * Created by PhpStorm.
 * User: vladyslav
 * Date: 14.03.15
 * Time: 1:16
 */

namespace Ensie\SimpleTextBundle\Event\EventListeners;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Ensie\SimpleTextBundle\Entity\SimpleText;
use Ensie\SimpleTextBundle\Entity\SimpleTextTranslation;

class PostUpdateListener {

    /**
     * @var CacheProvider
     */
    private $cache;

    /**
     * @param CacheProvider $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event){

        $entity = $event->getEntity();
        if ($entity instanceof SimpleText) {
            $this->cache->delete($entity->getIdentifier());
            $this->cache->save($entity->getIdentifier(),$entity);
        }

        if ($entity instanceof SimpleTextTranslation) {
            $identifier = $entity->getTranslatable()->getIdentifier();
            $this->cache->delete($identifier);
            $this->cache->save($identifier, $entity->getTranslatable());
        }
    }
}