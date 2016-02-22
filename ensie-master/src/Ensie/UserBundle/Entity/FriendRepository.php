<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:41
 */

namespace Ensie\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;

class FriendRepository extends EntityRepository{

    /**
     * @param EnsieUser $user
     * @param EnsieUser $friendEnsieUser
     * @return Friend
     */
    public function create(EnsieUser $user, EnsieUser $friendEnsieUser)
    {
        //TODO add event
        $friend = new Friend();
        $friend->setEnsieUser($user);
        $friend->setFriend($friendEnsieUser);
        $this->_em->persist($friend);
        return $friend;
    }

    /**
     * @param $friend
     * @return mixed
     */
    public function remove(Friend $friend)
    {
        //TODO add event
        $this->_em->remove($friend);
        return $friend;
    }

    /**
     * @param EnsieUser $user
     * @param EnsieUser $friendEnsieUser
     * @return mixed
     */
    public function findFriend(EnsieUser $user, EnsieUser $friendEnsieUser)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('friend');
        $qb->from('\Ensie\UserBundle\Entity\Friend', 'friend');
        $qb->where('friend.ensieUser = :user');
        $qb->andWhere('friend.friend = :friendEnsieUser');
        $qb->setParameter('user', $user);
        $qb->setParameter('friendEnsieUser', $friendEnsieUser);
        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param EnsieUser $user
     * @return array
     */
    public function getAllFavoritesByUser(EnsieUser $user)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('friend');
        $qb->from('\Ensie\UserBundle\Entity\Friend', 'friend');
        $qb->andWhere('friend.friend = :user');
        $qb->setParameter('user', $user);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param EnsieUser $user
     * @param EnsieUser $friendEnsieUser
     */
    public function deleteFavorite(EnsieUser $user, EnsieUser $friendEnsieUser)
    {
        $friend = $this->findFriend($user, $friendEnsieUser);
        $this->_em->remove($friend);
    }

} 