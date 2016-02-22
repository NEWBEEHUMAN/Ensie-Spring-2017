<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:05
 */

namespace Ensie\EnsieBundle\Event\Events;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Model\DefinitionValidation;
use Symfony\Component\EventDispatcher\Event;

class DefinitionValidationEvent extends Event
{
    /** @var DefinitionValidation  */
    protected $definitionValidation;

    /**
     * @param DefinitionValidation $definitionValidation
     */
    public function __construct(DefinitionValidation $definitionValidation)
    {
        $this->definitionValidation = $definitionValidation;
    }

    /**
     * @return DefinitionValidation
     */
    public function getDefinitionValidation()
    {
        return $this->definitionValidation;
    }
}