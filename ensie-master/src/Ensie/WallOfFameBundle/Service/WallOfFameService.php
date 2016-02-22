<?php

namespace Ensie\WallOfFameBundle\Service;
use Doctrine\ORM\Query;

/**
 * Created by PhpStorm.
 * User: vladyslav
 * Date: 01.05.15
 * Time: 21:55
 */

class WallOfFameService extends AbstractService {

    public function getUsersForWallOfFameByLocaleInLastMonth($locale){
        $now = new \DateTime('now');
        $monthAgo = new \DateTime('now');
        $monthAgo->modify('-1 month');

        /**
         * @var Query $query
         * */
        $query  =  $this->entityManager->createQueryBuilder()
            ->select('ensieuser, count(definition.id) AS HIDDEN definitionCount')
            ->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser')
            ->innerJoin('ensieuser.definitions', 'definition', 'WITH')
            ->innerJoin('definition.language', 'language', 'WITH', 'language.locale = :locale')
            ->where('definition.lastTextUpdate < :now')
            ->andWhere('ensieuser.enabledWriter = 1')
            ->andWhere('ensieuser.enabled = 1 ')
            ->andWhere('definition.lastTextUpdate > :monthAgo')
            ->setParameter('now', $now)
            ->setParameter('monthAgo', $monthAgo)
            ->groupBy('ensieuser.id')
            ->orderBy('definitionCount', 'DESC')
            ->setParameter('locale', $locale)
            ->getQuery();
        return $query->getResult();
    }

    public function getUsersForWallOfFameByLocale($locale){
        /**
         * @var Query $query
         * */
       $query  =  $this->entityManager->createQueryBuilder()
        ->select('ensieuser, count(definition.id) AS HIDDEN definitionCount')
        ->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser')
        ->innerJoin('ensieuser.definitions', 'definition', 'WITH')
        ->innerJoin('definition.language', 'language', 'WITH', 'language.locale = :locale')
        ->where('ensieuser.enabledWriter = 1')
        ->andWhere('ensieuser.enabled = 1 ')
        ->groupBy('ensieuser.id')
        ->orderBy('definitionCount', 'DESC')
        ->setParameter('locale', $locale)
        ->getQuery();
        return $query->getResult();
    }

    public function get100UsersForWallOfFameByLocale($locale){
        /**
         * @var Query $query
         * */
        $query  =  $this->entityManager->createQueryBuilder()
            ->select('ensieuser, count(definition.id) AS HIDDEN definitionCount')
            ->from('Ensie\UserBundle\Entity\EnsieUser', 'ensieuser')
            ->innerJoin('ensieuser.definitions', 'definition', 'WITH')
            ->innerJoin('definition.language', 'language', 'WITH', 'language.locale = :locale')
            ->where('ensieuser.enabledWriter = 1')
            ->andWhere('ensieuser.enabled = 1 ')
            ->groupBy('ensieuser.id')
            ->orderBy('definitionCount', 'DESC')
            ->setParameter('locale', $locale)
            ->setMaxResults(100)
            ->getQuery();
        return $query->getResult();
    }

}