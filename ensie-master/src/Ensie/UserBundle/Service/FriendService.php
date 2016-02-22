<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 9-8-14
 * Time: 10:53
 */

namespace Ensie\UserBundle\Service;



use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\FriendRepository;
use Ensie\UserBundle\Event\Events\UserEvents;
use Ensie\UserBundle\Event\Events\UserFriendEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class FriendService {

    /** @var  FriendRepository */
    private $friendRepository;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    protected $dispatcher;

    /**
     * @param FriendRepository $friendRepository
     * @param EventDispatcherInterface $dispatcher
     */
    function __construct(FriendRepository $friendRepository, EventDispatcherInterface $dispatcher)
    {
        $this->friendRepository = $friendRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param EnsieUser $user
     * @param EnsieUser $friendEnsieUser
     * @return bool
     */
    public function isFriend(EnsieUser $user, EnsieUser $friendEnsieUser){
        $friend = $this->friendRepository->findFriend($user, $friendEnsieUser);
        return (!empty($friend) ? true : false);
    }

    /**
     * @param EnsieUser $user
     * @param EnsieUser $ensieUserFriend
     * @return bool
     */
    public function addFriend(EnsieUser $user, EnsieUser $ensieUserFriend){
        if(!$this->isFriend($user,$ensieUserFriend)){
            $friend = $this->friendRepository->create($user, $ensieUserFriend);
            $this->dispatcher->dispatch(UserEvents::USER_FRIEND_ADDED, new UserFriendEvent($friend));
            return true;
        }
        return false;
    }

    /**
     * @param EnsieUser $user
     * @param EnsieUser $friendEnsieUser
     * @return bool
     */
    public function removeFriend(EnsieUser $user, EnsieUser $friendEnsieUser){
        if($this->isFriend($user,$friendEnsieUser)){
            $friend = $this->friendRepository->findFriend($user, $friendEnsieUser);
            $this->friendRepository->remove($friend);
            return true;
        }
        return false;
    }
} 