<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:05
 */

namespace Ensie\EnsieBundle\Event\Events;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\Spam;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\EventDispatcher\Event;


class DefinitionEvent extends Event
{
    /** @var \Ensie\EnsieBundle\Entity\Definition  */
    protected $definition;

    /**
     * @param Definition $definition
     */
    public function __construct(Definition $definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return Definition
     */
    public function getDefinition()
    {
        return $this->definition;
    }
}