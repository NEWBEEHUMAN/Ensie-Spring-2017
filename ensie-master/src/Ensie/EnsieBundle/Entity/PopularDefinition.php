<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 25-10-14
 * Time: 11:25
 */

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="Ensie\EnsieBundle\Entity\PopularDefinitionRepository")
 * @ORM\Table()
 */
class PopularDefinition {

    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Definition
     *
     * @ORM\ManyToOne(targetEntity="Definition")
     * @ORM\JoinColumn(name="definition_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $definition;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Ensie\LanguageBundle\Entity\Language", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $language;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", length=9)
     */
    private $position;

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
     * @param int $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return int
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

} 