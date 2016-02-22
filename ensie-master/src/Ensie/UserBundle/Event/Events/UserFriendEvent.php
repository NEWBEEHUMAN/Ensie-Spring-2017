<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 13-8-14
 * Time: 20:05
 */

namespace Ensie\UserBundle\Event\Events;


use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\Friend;
use Symfony\Component\EventDispatcher\Event;

class UserFriendEvent extends Event{

    /** @var  Friend */
    protected $friend;

    public function __construct(Friend $friend)
    {
        $this->friend = $friend;
    }

    /**
     * @return \Ensie\UserBundle\Entity\Friend
     */
    public function getFriend()
    {
        return $this->friend;
    }
}