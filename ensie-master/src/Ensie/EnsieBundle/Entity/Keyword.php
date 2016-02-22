<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Keyword
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ensie\EnsieBundle\Entity\KeywordRepository")
 *
 */
class Keyword
{
    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\Sluggable\Sluggable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Definition")
     * @ORM\JoinColumn(name="definition_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $definition;

    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", length=255)
     */
    private $word;

    /**
     * @var boolean
     *
     * @ORM\Column(name="definitionable", type="boolean")
     */
    private $definitionable = 1;

    public function getSluggableFields()
    {
        return [ 'word' ];
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Definition $definition
     */
    public function setDefinition(Definition $definition)
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

    /**
     * Set word
     *
     * @param string $word
     * @return Keyword
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Keyword
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set definitionable
     *
     * @param boolean $definitionable
     * @return Keyword
     */
    public function setDefinitionable($definitionable)
    {
        $this->definitionable = $definitionable;

        return $this;
    }

    /**
     * Get definition
     *
     * @return boolean 
     */
    public function getDefinitionable()
    {
        return $this->definitionable;
    }
}
