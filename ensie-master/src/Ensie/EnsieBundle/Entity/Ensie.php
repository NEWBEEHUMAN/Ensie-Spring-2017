<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\UserBundle\Entity\EnsieUser;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Ensie
 *
 * $user\Table()
 * @ORM\Entity(repositoryClass="Ensie\EnsieBundle\Entity\EnsieRepository")
 */
class Ensie
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
    protected $id;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $ensieUser;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="\Ensie\LanguageBundle\Entity\Language")
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Definition", mappedBy="ensie")
     * @ORM\OrderBy({"word" = "ASC"})
     */
    protected $definitions;

    /**
     *
     */
    function __construct()
    {
        $this->definitions = new ArrayCollection();
    }

    function __toString()
    {
        $language = $this->getLanguage();
        $locale = 'onbekend';
        if($language instanceof Language){
            $locale = $this->getLanguage()->getLocale();
        }
        return $this->getTitle() . ' | ' . $locale;
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
     * Set title
     *
     * @param string $title
     * @return Ensie
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Ensie
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
     * @param EnsieUser $ensieUser
     * @return $this
     */
    public function setEnsieUser(EnsieUser $ensieUser)
    {
        $this->ensieUser = $ensieUser;

        return $this;
    }

    /**
     * @return int
     */
    public function getEnsieUser()
    {
        return $this->ensieUser;
    }

    /**
     * Set languageId
     *
     * @param Language $language
     * @return Definition
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $definitions
     */
    public function setDefinitions($definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    public function getSluggableFields()
    {
        return [ 'title' ];
    }


}
