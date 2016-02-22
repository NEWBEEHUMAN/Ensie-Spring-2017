<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 8-8-14
 * Time: 12:28
 */

namespace Ensie\StatsBundle\Service;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\UserBundle\Entity\EnsieUser;

class EmailNotificationService extends AbstractStatsService {

    CONST START_DAY = "now";
    CONST INTERVAL = "2 weeks";

    public function getStats(EnsieUser $ensieUser){
        $endDate = new \DateTime(date('Y-m-d', strtotime(self::START_DAY, time())));
        $startDate = clone($endDate);
        $startDate = $startDate->sub(\DateInterval::createFromDateString(self::INTERVAL));
        //Cache this?
        $result['FOLLOWERS'] = $this->getNewFollowers($ensieUser, $startDate, $endDate);
        $result['OWNDEFINITIONRATING'] = $this->getDefinitionRating($ensieUser, $startDate, $endDate);
        $result['NEWDEFINITIONS'] = $this->getNewDefinitionsFromExperts($ensieUser, $startDate, $endDate);
        return $result;
    }

    /**
     * @param EnsieUser $ensieUser
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array
     */
    private function getNewFollowers(EnsieUser $ensieUser, \DateTime $startDate, \DateTime $endDate){
        /** @var QueryBuilder $query */
        $query = $this->entityManager->createQueryBuilder();
        $query->select('friend');
        $query->from('\Ensie\UserBundle\Entity\Friend', 'friend');
        $query->where('friend.friend = :ensieUser');
        $query->andWhere('friend.createdAt > :startDate AND friend.createdAt < :endDate');
        $query->setParameter('startDate', $startDate);
        $query->setParameter('endDate', $endDate);
        $query->setParameter('ensieUser', $ensieUser);
        return $query->getQuery()->getResult();
    }

    /**
     * @param EnsieUser $ensieUser
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array
     */
    private function getDefinitionRating(EnsieUser $ensieUser, \DateTime $startDate, \DateTime $endDate){
        /** @var QueryBuilder $query */
        $query = $this->entityManager->createQueryBuilder();
        $query->select('definition, AVG(rating.rating) AS average');
        $query->from('Ensie\EnsieBundle\Entity\Definition', 'definition');
        $query->innerJoin('definition.ratings', 'rating');
        $query->andWhere('definition.ensieUser = :ensieUser');
        $query->andWhere('rating.createdAt > :startDate AND rating.createdAt < :endDate');
        $query->groupBy('definition.id');
        $query->setParameter('startDate', $startDate);
        $query->setParameter('endDate', $endDate);
        $query->setParameter('ensieUser', $ensieUser);
        return $query->getQuery()->getResult();
    }

    /**
     * @param EnsieUser $ensieUser
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return array
     */
    private function getNewDefinitionsFromExperts(EnsieUser $ensieUser, \DateTime $startDate, \DateTime $endDate){
        /** @var QueryBuilder $query */
        $query = $this->entityManager->createQueryBuilder();
        $query->select('definition');
        $query->from('Ensie\EnsieBundle\Entity\Definition', 'definition');
        $query->innerJoin('definition.ensieUser', 'ensieUser');
        $query->innerJoin('Ensie\UserBundle\Entity\Friend', 'friend', 'WITH', 'friend.friend = ensieUser AND friend.ensieUser = :ensieUser');
        $query->andWhere('definition.createdAt > :startDate AND definition.createdAt < :endDate');
        $query->andWhere('ensieUser.enabledWriter = 1');
        $query->orderBy('definition.ensieUser');
        $query->setParameter('startDate', $startDate);
        $query->setParameter('endDate', $endDate);
        $query->setParameter('ensieUser', $ensieUser);
        return $query->getQuery()->getResult();
    }

} 