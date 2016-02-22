<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:22
 */

namespace Ensie\SubscriptionBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="Ensie\SubscriptionBundle\Entity\SubscriptionRepository")
 * @ORM\Table()
 */
class Subscription {

    use ORMBehaviors\Translatable\Translatable;
    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=255)
     */
    private $identifier;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isCompany = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $isMostSold = false;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $wordAmount = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $urlShowAtDefinition = false;

    /**
     * @var integer
     *
     * @ORM\Column(type="boolean")
     */
    private $wordUniqueness = false;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $position = 0;

    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    public function __toString()
    {
        return  $this->getIdentifier();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param boolean $isCompany
     */
    public function setIsCompany($isCompany)
    {
        $this->isCompany = $isCompany;
    }

    /**
     * @return boolean
     */
    public function getIsCompany()
    {
        return $this->isCompany;
    }

    /**
     * @param boolean $isMostSold
     */
    public function setIsMostSold($isMostSold)
    {
        $this->isMostSold = $isMostSold;
    }

    /**
     * @return boolean
     */
    public function getIsMostSold()
    {
        return $this->isMostSold;
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

    /**
     * @param boolean $urlShowAtDefinition
     */
    public function setUrlShowAtDefinition($urlShowAtDefinition)
    {
        $this->urlShowAtDefinition = $urlShowAtDefinition;
    }

    /**
     * @return boolean
     */
    public function getUrlShowAtDefinition()
    {
        return $this->urlShowAtDefinition;
    }

    /**
     * @param int $wordUniqueness
     */
    public function setWordUniqueness($wordUniqueness)
    {
        $this->wordUniqueness = $wordUniqueness;
    }

    /**
     * @return int
     */
    public function getWordUniqueness()
    {
        return $this->wordUniqueness;
    }

    /**
     * @param int $wordAmount
     */
    public function setWordAmount($wordAmount)
    {
        $this->wordAmount = $wordAmount;
    }

    /**
     * @return int
     */
    public function getWordAmount()
    {
        return $this->wordAmount;
    }



}