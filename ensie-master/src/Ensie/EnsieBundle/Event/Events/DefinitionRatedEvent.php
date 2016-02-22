<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:05
 */

namespace Ensie\EnsieBundle\Event\Events;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\Rating;
use Ensie\EnsieBundle\Model\DefinitionValidation;
use Symfony\Component\EventDispatcher\Event;

class DefinitionRatedEvent extends Event
{
    /** @var \Ensie\EnsieBundle\Entity\Rating */
    protected $rating;

    function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return Rating
     */
    public function getRating()
    {
        return $this->rating;
    }

}