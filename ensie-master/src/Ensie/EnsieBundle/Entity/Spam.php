<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ensie\UserBundle\Entity\EnsieUser;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Rating
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ensie\EnsieBundle\Entity\SpamRepository")
 */
class Spam
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
     * @ORM\ManyToOne(targetEntity="Definition")
     * @ORM\JoinColumn(name="definition_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $definition;

    /**
     * @var EnsieUser
     *
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

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
     * @return integer 
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }
}
