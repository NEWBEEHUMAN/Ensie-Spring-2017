<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:22
 */

namespace Ensie\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="Ensie\SubscriptionBundle\Entity\SubscriptionLogRepository")
 * @ORM\Table()
 */
class SubscriptionLog {

    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Ensie\SubscriptionBundle\Entity\Subscription", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $subscription;

    /**
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser", cascade={"persist"})
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $email = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $companyName = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $ipAddress = '';

    /**
     * @param string $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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

    /**
     * @param int $subscription
     */
    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * @return int
     */
    public function getSubscription()
    {
        return $this->subscription;
    }

    /**
     * @param int $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }



}