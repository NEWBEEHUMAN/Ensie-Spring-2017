<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ensie\UserBundle\Entity\EnsieUser;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Rating
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ensie\EnsieBundle\Entity\RatingRepository")
 */
class Rating
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
     * @var Definition
     *
     * @ORM\ManyToOne(targetEntity="Definition")
     * @ORM\JoinColumn(name="definition_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $definition;

    /**
     * @var EnsieUser
     *
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @var integer
     *
     * @ORM\Column(name="rating", type="integer")
     */
    private $rating;

    /**
     * @var string
     *
     * @ORM\Column(name="feedback", type="text", nullable=true)
     */
    private $feedback;

    /**
     * @var string
     *
     * @ORM\Column(name="ipaddress", type="string", length=255)
     */
    private $ipAddress;


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
     * Set definition
     *
     * @param Definition $definition
     * @return Rating
     */
    public function setDefinition($definition)
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Get definition
     *
     * @return Definition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param EnsieUser $user
     * @return $this
     */
    public function setUser(EnsieUser $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return EnsieUser
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param $ipAddress
     * @return $this
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * @param $rating
     * @return $this
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * @return string
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $feedback
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * @return string
     */
    public function getFeedback()
    {
        return $this->feedback;
    }
}
