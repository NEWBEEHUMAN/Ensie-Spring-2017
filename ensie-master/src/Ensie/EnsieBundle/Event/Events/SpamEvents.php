<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:02
 */

namespace Ensie\EnsieBundle\Event\Events;


final class SpamEvents
{
    /**
     * The store.order event is thrown each time an order is created
     * in the system.
     *
     * The event listener receives an
     * Acme\StoreBundle\Event\FilterOrderEvent instance.
     *
     * @var string
     */
    const ENSIE_DEFINITION_SPAM_REPORTED = 'ensie.definition.spam_reported';
}