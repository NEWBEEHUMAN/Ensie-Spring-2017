<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 14-9-14
 * Time: 18:32
 */

namespace Ensie\EnsieBundle\Model;


use Ensie\EnsieBundle\Entity\Definition;

class DefinitionValidation {

    /** @var  Definition */
    private $definition;

    private $feedback;

    /**
     * @param \Ensie\EnsieBundle\Entity\Definition $definition
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;
    }

    /**
     * @return \Ensie\EnsieBundle\Entity\Definition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param mixed $feedback
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * @return mixed
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

} 