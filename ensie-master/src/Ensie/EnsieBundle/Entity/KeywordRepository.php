<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\UserBundle\Entity\EnsieUser;

/**
 * KeywordRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class KeywordRepository extends EntityRepository
{

    public function getAllDefinitionableKeywords(EnsieUser $ensieUser, Language $language){
        $subQuery = $this->_em->createQueryBuilder()
            ->select('definedWords.word')
            ->from('Ensie\EnsieBundle\Entity\Definition', 'definedWords')
            ->innerJoin('definedWords.ensieUser','ensieUser')
            ->where('ensieUser = :user1')
            ->andWhere('definedWords.language = :language')
            ->setParameter('user1', $ensieUser)
            ->setParameter('language', $language)
            ->getDQL();

        $qb = $this->createQueryBuilder('keyword');
        $qb->select('keyword');
        $qb->innerJoin('keyword.definition', 'definition', 'WITH', 'definition.ensieUser = :user and definition.word != keyword.word');
        $qb->andWhere('definition.language = :language');
        $qb->andWhere($qb->expr()->notIn('keyword.word', $subQuery));
        $qb->setParameter('language', $language);
        $qb->setParameter('user1', $ensieUser);
        $qb->setParameter('user', $ensieUser);
        return $qb->getQuery()->execute();
    }

    public function getRandomDefinitionableKeywords(EnsieUser $ensieUser, Language $language, $limit){
        $keywords = $this->getAllDefinitionableKeywords($ensieUser, $language);
        shuffle($keywords);
        return array_slice($keywords, 0, $limit);
    }

}
