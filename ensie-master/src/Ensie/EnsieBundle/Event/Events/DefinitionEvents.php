<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:02
 */

namespace Ensie\EnsieBundle\Event\Events;


final class DefinitionEvents
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
    const DEFINITION_VALIDATE = 'ensie.definition.validate';
    const DEFINITION_CREATED = 'ensie.definition.created';
    const DEFINITION_REMOVED = 'ensie.definition.removed';
    const DEFINITION_ACCEPTED = 'ensie.definition.accepted';
    const DEFINITION_REJECTED = 'ensie.definition.rejected';
    const DEFINITION_RATED = 'ensie.definition.rated';
    const DEFINITION_RATED_FEEDBACK = 'ensie.definition.rated_feedback';
}