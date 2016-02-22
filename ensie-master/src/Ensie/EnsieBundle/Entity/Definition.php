<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\UserBundle\Entity\EnsieUser;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Definition
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ensie\EnsieBundle\Entity\DefinitionRepository")
 */
class Definition
{
    const NEW_DEFINITION = 'new';
    const ACTIVE_DEFINITION = 'active';
    const DISABLED_DEFINITION = 'disabled';

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
     * @ORM\ManyToOne(targetEntity="\Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="ensie_user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $ensieUser;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Ensie\EnsieBundle\Entity\Subcategory", inversedBy="definitions")
     * @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $subcategory;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Ensie")
     * @ORM\JoinColumn(name="ensie_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $ensie;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="\Ensie\LanguageBundle\Entity\Language")
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", length=255)
     */
    private $word;

    /**
     * @var string
     *
     * @ORM\Column(name="definition", type="text")
     */
    private $definition;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status = self::NEW_DEFINITION;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $validated = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $startViewCount = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $viewCount = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="rating", type="float")
     */
    private $ratingCount = 0;

    /**
     * @var string
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastTextUpdate;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="definition", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $ratings;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="View", mappedBy="definition", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    private $views;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Spam", mappedBy="definition", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $spam;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Keyword", mappedBy="definition", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $extraLinkText;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $extraLinkUrl;

    function __construct()
    {
        $this->views = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->spam = new ArrayCollection();
        $this->keywords = new ArrayCollection();
    }

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
     * @param EnsieUser $ensieUser
     * @return $this
     */
    public function setEnsieUser(EnsieUser $ensieUser)
    {
        $this->ensieUser = $ensieUser;

        return $this;
    }

    /**
     * @return ensieUser
     */
    public function getEnsieUser()
    {
        return $this->ensieUser;
    }

    /**
     * Set ensie
     *
     * @param Ensie $ensie
     * @return Definition
     */
    public function setEnsie($ensie)
    {
        $this->ensie = $ensie;

        return $this;
    }

    /**
     * Get ensie
     *
     * @return Ensie
     */
    public function getEnsie()
    {
        return $this->ensie;
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
     * Set word
     *
     * @param string $word
     * @return Definition
     */
    public function setWord($word)
    {
        $this->updateLastTextUpdate();
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
     * Set definition
     *
     * @param string $definition
     * @return Definition
     */
    public function setDefinition($definition)
    {
        $this->updateLastTextUpdate();
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get definition
     *
     * @return string 
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Definition
     */
    public function setDescription($description)
    {
        $this->updateLastTextUpdate();
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Definition
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param bool $validated
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;
    }

    /**
     * @return bool
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * @param int $startViewCount
     */
    public function setStartViewCount($startViewCount)
    {
        $this->startViewCount = $startViewCount;
    }

    /**
     * @return int
     */
    public function getStartViewCount()
    {
        return $this->startViewCount;
    }

    /**
     * @param $viewCount
     * @return $this
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = $viewCount;

        return $this;
    }

    /**
     * @return int
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * @param $ratingCount
     * @return $this
     */
    public function setRatingCount($ratingCount)
    {
        $this->ratingCount = $ratingCount;

        return $this;
    }

    /**
     * @return string
     */
    public function getRatingCount()
    {
        return $this->ratingCount;
    }


    public function updateLastTextUpdate()
    {
        $this->setLastTextUpdate(new \DateTime('now'));
    }
    /**
     * @param \DateTime $lastTextUpdate
     */
    public function setLastTextUpdate(\DateTime $lastTextUpdate)
    {
        $this->lastTextUpdate = $lastTextUpdate;
    }

    /**
     * @return string
     */
    public function getLastTextUpdate()
    {
        return $this->lastTextUpdate;
    }


    /**
     * @param $views
     * @return $this
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * @return string
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param $ratings
     * @return $this
     */
    public function setRatings($ratings)
    {
        $this->ratings = $ratings;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $spam
     */
    public function setSpam($spam)
    {
        $this->spam = $spam;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getSpam()
    {
        return $this->spam;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $keywords
     */
    public function setKeywords($keywords)
    {
        $this->keywords = $keywords;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getKeywords()
    {
        return $this->keywords;
    }

    /**
     * @param Keyword $keyword
     */
    public function addKeyword(Keyword $keyword)
    {
        $this->keywords->add($keyword);
        $keyword->setDefinition($this);
    }

    /**
     * @param Keyword $keyword
     */
    public function removeKeyword(Keyword $keyword)
    {
        $this->keywords->remove($keyword);
    }

    function __toString()
    {
        $ensieUser = $this->getEnsieUser();
        $name = 'onbekend';
        if($ensieUser instanceof EnsieUser){
            $name = $this->getEnsieUser()->getFormattedName();
        }
        $language = $this->getLanguage();
        $locale = 'onbekend';
        if($language instanceof Language){
            $locale = $this->getLanguage()->getLocale();
        }
        return $this->word . ' | ' . $name . ' | ' . $locale;
    }

    /**
     * @return int
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * @param int $subcategory
     */
    public function setSubcategory($subcategory)
    {
        $this->subcategory = $subcategory;
    }

    /**
     * @return string
     */
    public function getExtraLinkText()
    {
        return $this->extraLinkText;
    }

    /**
     * @param string $extraLinkText
     */
    public function setExtraLinkText($extraLinkText)
    {
        $this->extraLinkText = $extraLinkText;
    }

    /**
     * @return string
     */
    public function getExtraLinkUrl()
    {
        return $this->extraLinkUrl;
    }

    /**
     * @param string $extraLinkUrl
     */
    public function setExtraLinkUrl($extraLinkUrl)
    {
        $this->extraLinkUrl = $extraLinkUrl;
    }
}
