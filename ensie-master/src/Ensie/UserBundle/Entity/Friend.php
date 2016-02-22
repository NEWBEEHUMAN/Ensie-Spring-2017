<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:22
 */

namespace Ensie\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity(repositoryClass="Ensie\UserBundle\Entity\FriendRepository")
 * @ORM\Table()
 */
class Friend {

    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var EnsieUser
     *
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $ensieUser;

    /**
     * @var EnsieUser
     *
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="user_friend_id", referencedColumnName="id", nullable=true)
     */
    protected $friend;

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param \Ensie\UserBundle\Entity\EnsieUser $ensieUser
     */
    public function setEnsieUser(EnsieUser $ensieUser)
    {
        $this->ensieUser = $ensieUser;
    }

    /**
     * @return \Ensie\UserBundle\Entity\EnsieUser
     */
    public function getEnsieUser()
    {
        return $this->ensieUser;
    }

    /**
     * @param \Ensie\UserBundle\Entity\EnsieUser $friend
     */
    public function setFriend(EnsieUser $friend)
    {
        $this->friend = $friend;
    }

    /**
     * @return \Ensie\UserBundle\Entity\EnsieUser
     */
    public function getFriend()
    {
        return $this->friend;
    }
}