<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * View
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ensie\EnsieBundle\Entity\ViewRepository")
 */
class View
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $user;

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
     * @param Definition $definition
     * @return $this
     */
    public function setDefinition(Definition $definition)
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
     * Set user
     *
     * @param integer $user
     * @return View
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return integer 
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
}
