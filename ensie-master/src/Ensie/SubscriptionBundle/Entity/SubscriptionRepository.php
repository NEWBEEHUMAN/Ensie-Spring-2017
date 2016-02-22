<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:41
 */

namespace Ensie\SubscriptionBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SubscriptionRepository extends EntityRepository{

    /**
     * @return array
     */
    public function getCompanySubscriptions(){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('subscription, translations');
        $qb->from('Ensie\SubscriptionBundle\Entity\Subscription', 'subscription');
        $qb->leftJoin('subscription.translations', 'translations');
        $qb->where('subscription.isCompany = 1');
        $qb->orderBy('subscription.position', 'asc');
        return $qb->getQuery()->getResult();
    }

}
