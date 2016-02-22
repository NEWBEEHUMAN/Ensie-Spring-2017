<?php

namespace Ensie\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ensie\UserBundle\Entity\EnsieUser;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ensie\NotificationBundle\Entity\NotificationRepository")
 */
class Notification implements ViewdateInterface
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
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected $ensieUser;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="create_user_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $createUser;

    /**
     * @var string
     *
     * @ORM\Column(name="notification", type="string", length=255)
     */
    private $notification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="view_date", type="datetime", nullable=true)
     */
    private $viewDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="viewed", type="boolean", nullable=true)
     */
    private $viewed = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="type", type="string", length=25, nullable=true)
     */
    private $type = '';


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
     * @param EnsieUser $createUser
     */
    public function setCreateUser(EnsieUser $createUser)
    {
        $this->createUser = $createUser;
    }

    /**
     * @return EnsieUser
     */
    public function getCreateUser()
    {
        return $this->createUser;
    }

    /**
     * @param EnsieUser $ensieUser
     */
    public function setEnsieUser(EnsieUser $ensieUser)
    {
        $this->ensieUser = $ensieUser;
    }

    /**
     * @return EnsieUser
     */
    public function getEnsieUser()
    {
        return $this->ensieUser;
    }

    /**
     * @param string $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return string
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param \DateTime $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \DateTime $viewDate
     */
    public function setViewDate(\DateTime $viewDate)
    {
        $this->viewDate = $viewDate;
    }

    /**
     * @return \DateTime
     */
    public function getViewDate()
    {
        return $this->viewDate;
    }

    /**
     * @param boolean $viewed
     */
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;
    }

    /**
     * @return boolean
     */
    public function getViewed()
    {
        return $this->viewed;
    }


}
