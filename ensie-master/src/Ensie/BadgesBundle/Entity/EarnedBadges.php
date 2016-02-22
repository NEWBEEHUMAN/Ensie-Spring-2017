<?php

namespace Ensie\BadgesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * EarnedBadges
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ensie\BadgesBundle\Entity\EarnedBadgesRepository")
 */
class EarnedBadges
{

    use ORMBehaviors\Timestampable\Timestampable;

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
     * @ORM\Column(name="user_id", type="integer")
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="badges_id", type="integer")
     */
    private $badgesId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="selected", type="boolean")
     */
    private $selected;


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
     * Set userId
     *
     * @param integer $userId
     * @return EarnedBadges
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set badgesId
     *
     * @param integer $badgesId
     * @return EarnedBadges
     */
    public function setBadgesId($badgesId)
    {
        $this->badgesId = $badgesId;

        return $this;
    }

    /**
     * Get badgesId
     *
     * @return integer 
     */
    public function getBadgesId()
    {
        return $this->badgesId;
    }

    /**
     * Set selected
     *
     * @param boolean $selected
     * @return EarnedBadges
     */
    public function setSelected($selected)
    {
        $this->selected = $selected;

        return $this;
    }

    /**
     * Get selected
     *
     * @return boolean 
     */
    public function getSelected()
    {
        return $this->selected;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return EarnedBadges
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }
}
