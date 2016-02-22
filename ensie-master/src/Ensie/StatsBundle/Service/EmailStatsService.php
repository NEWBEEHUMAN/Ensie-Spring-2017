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

class EmailStatsService extends AbstractStatsService {

    CONST START_DAY = "now";
    CONST INTERVAL = "2 weeks";

    public function getStats(EnsieUser $ensieUser){
        $endDate = new \DateTime(date('Y-m-d', strtotime(self::START_DAY, time())));
        $startDate = clone($endDate);
        $startDate = $startDate->sub(\DateInterval::createFromDateString(self::INTERVAL));
        //Cache this?
        $result['TOTAL_VIEWS'] = $this->getViewCount($ensieUser);
        $top = $this->getTopDefinition($ensieUser, $startDate, $endDate);
        $result['TOP_VIEW_COUNT'] = $top['viewCount'];
        $result['TOP_WORD'] = $top['word'];
        $result['DEFINITION_COUNT'] = $this->getDefinitionCount($ensieUser);
        return $result;
    }

    /**
     * @param EnsieUser $ensieUser
     * @return mixed
     */
    private function getViewCount(EnsieUser $ensieUser){
        /** @var Query $query */
        $query = $this->entityManager->createQuery('
        SELECT SUM(definition.viewCount)
        FROM Ensie\EnsieBundle\Entity\Definition definition
        WHERE definition.ensieUser = :ensieUser
        ');
        $query->setParameter('ensieUser', $ensieUser);
        $result = $query->getScalarResult(Query::HYDRATE_ARRAY);
        if(isset($result[0][1])){
            $result = $result[0][1];
        } else {
            $result = 0;
        }
        return $result;
    }

    /**
     * @param EnsieUser $ensieUser
     * @param $startDate
     * @param $endDate
     * @return array
     */
    private function getTopDefinition(EnsieUser $ensieUser, \DateTime $startDate, \DateTime $endDate){
        /** @var Query $query */
        $query = $this->entityManager->createQuery('
        SELECT count(views.id) as viewCount, definition.word
        FROM Ensie\EnsieBundle\Entity\Definition definition
        INNER JOIN Ensie\EnsieBundle\Entity\View views WITH definition.id = views.definition AND views.createdAt > :startDate AND views.createdAt < :endDate
        WHERE definition.ensieUser = :ensieUser
        GROUP BY views.definition
        ORDER BY viewCount DESC
        ');
        $query->setMaxResults(1);
        $query->setParameter('startDate', $startDate);
        $query->setParameter('endDate', $endDate);
        $query->setParameter('ensieUser', $ensieUser);
        $result = $query->getResult(Query::HYDRATE_ARRAY);
        if(isset($result[0])){
            $result = $result[0];
        } else {
            $result = null;
        }
        return $result;
    }

    /**
     * @param EnsieUser $ensieUser
     * @return mixed
     */
    private function getDefinitionCount(EnsieUser $ensieUser){
        /** @var Query $query */
        $query = $this->entityManager->createQuery('
        SELECT COUNT(definition)
        FROM Ensie\EnsieBundle\Entity\Definition definition
        WHERE definition.ensieUser = :ensieUser
        ');
        $query->setParameter('ensieUser', $ensieUser);
        $result = $query->getScalarResult(Query::HYDRATE_ARRAY);
        if(isset($result[0][1])){
            $result = $result[0][1];
        } else {
            $result = 0;
        }
        return $result;
    }

    /**
     * @param EnsieUser $ensieUser
     * @return mixed
     */
    private function getFavoriteCount(EnsieUser $ensieUser){
        //TODO make the favorite count
        /** @var Query $query */
        $query = $this->entityManager->createQuery('SELECT COUNT(friend) as friendCount FROM Ensie\UserBundle\Entity\Friend friend WHERE friend.friend = :ensieUser');
        $query->setParameter('ensieUser', $ensieUser);
        return $query->getSingleScalarResult();
    }

} 