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

class BasicStatsService extends AbstractStatsService {

    /**
     * @param EnsieUser $ensieUser
     * @param Language $language
     * @return array
     */
    public function getBasicUserStats(EnsieUser $ensieUser, Language $language){
        //Cache this?
        $result['ensieCount'] = $this->getEnsieCount($ensieUser, $language);
        $definitionStats = $this->getDefinitionAndViewCount($ensieUser, $language);
        $result['definitionCount'] = $definitionStats['definitionCount'];
        $result['viewCount'] = $definitionStats['viewCount'];
        $result['favoriteCount'] = $this->getFavoriteCount($ensieUser);
        return $result;
    }

    /**
     * @param EnsieUser $ensieUser
     * @param Language $language
     * @return mixed
     */
    private function getEnsieCount(EnsieUser $ensieUser, Language $language){
        /** @var Query $query */

        $query = $this->entityManager->createQuery("
        SELECT count(ensie.id)
        FROM Ensie\EnsieBundle\Entity\Ensie ensie
        WHERE ensie.ensieUser = :ensieUser
          AND ensie.language = :language
          AND ( SELECT COUNT(definition.id)
                FROM Ensie\EnsieBundle\Entity\Definition definition
                WHERE definition.ensie = ensie
              ) > 0");
        $query->setParameter('ensieUser', $ensieUser);
        $query->setParameter('language', $language);
        return $query->getSingleScalarResult();
    }

    /**
     * @param EnsieUser $ensieUser
     * @param Language $language
     * @return mixed
     */
    private function getDefinitionAndViewCount(EnsieUser $ensieUser, Language $language){
        /** @var Query $query */
        $query = $this->entityManager->createQuery('
        SELECT COUNT(definition) as definitionCount, SUM(definition.viewCount) as viewCount
        FROM Ensie\EnsieBundle\Entity\Definition definition
        WHERE definition.ensieUser = :ensieUser
            AND definition.language = :language
        ');
        $query->setParameter('ensieUser', $ensieUser);
        $query->setParameter('language', $language);
        return $query->getSingleResult(Query::HYDRATE_ARRAY);
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