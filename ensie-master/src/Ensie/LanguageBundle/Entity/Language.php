<?php

namespace Ensie\LanguageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Language
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="locale_unique",columns={"locale"})})
 * @ORM\Entity(repositoryClass="Ensie\LanguageBundle\Entity\LanguageRepository")
 */
class Language
{

    use ORMBehaviors\Translatable\Translatable;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=50)
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="writeable", type="boolean")
     */
    private $writeable;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->locale;
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
     * @param $writeable
     * @return $this
     */
    public function setWriteable($writeable)
    {
        $this->writeable = $writeable;

        return $this;
    }

    /**
     * @return string
     */
    public function getWriteable()
    {
        return $this->writeable;
    }



    /**
     * Set locale
     *
     * @param string $locale
     * @return Language
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Get locale
     *
     * @return string 
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getName(){
        if(count($this->translations) > 0){
            return $this->translations->first()->getName();
        }else{
            return $this->translate()->getName();
        }
    }
}
