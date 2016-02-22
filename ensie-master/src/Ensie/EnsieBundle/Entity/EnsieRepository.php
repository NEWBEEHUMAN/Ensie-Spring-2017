<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\UserBundle\Entity\EnsieUser;

/**
 * EnsieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EnsieRepository extends EntityRepository
{

    public function createEnsie($title, EnsieUser $ensieUser, Language $language){
        $ensie = new Ensie();
        $ensie->setTitle($title);
        $ensie->setEnsieUser($ensieUser);
        $ensie->setLanguage($language);
        $this->_em->persist($ensie);
        return $ensie;
    }

    public function getAllEnsiesByUser(EnsieUser $user, Language $language = null){
        $qb = $this->createQueryBuilder('ensie');
        $qb->select('ensie, ensieUser');
        $qb->innerJoin('ensie.ensieUser','ensieUser', 'WITH', 'ensieUser  = :user');
        if(!empty($language)){
            $qb->where('ensie.language = :language');
            $qb->setParameter('language', $language);
        }
        $qb->setParameter('user', $user);
        $qb->orderBy('ensie.title', 'ASC');
        return $qb->getQuery()->execute();
    }

    public function getEnsiesByUser(EnsieUser $user, Language $language = null){
        $qb = $this->createQueryBuilder('ensie');
        $qb->select('ensie, ensieUser, definitions');
        $qb->innerJoin('ensie.ensieUser','ensieUser', 'WITH', 'ensieUser  = :user');
        $qb->innerJoin('ensie.definitions','definitions');
        if(!empty($language)){
            $qb->where('definitions.language = :language');
            $qb->setParameter('language', $language);
        }
        $qb->setParameter('user', $user);
        $qb->orderBy('ensie.title', 'ASC');
        return $qb->getQuery()->execute();
    }

    public function getByTitleUserLanguage($title, EnsieUser $user, Language $language){
        $qb = $this->createQueryBuilder('ensie');
        $qb->where('ensie.ensieUser = :user');
        $qb->andWhere('ensie.title = :title');
        $qb->andWhere('ensie.language = :language');
        $qb->setParameter('user', $user);
        $qb->setParameter('title', $title);
        $qb->setParameter('language', $language);
        return $qb->getQuery()->execute();
    }

    public function giveEnsieTitleAndUser($title, EnsieUser $user, Language $language){
        $ensie = $this->getByTitleUserLanguage($title, $user, $language);
        if(!empty($ensie) and isset($ensie[0])){
            return $ensie[0];
        } else {
            $ensie = $this->createEnsie($title, $user, $language);
            $this->getEntityManager()->flush();
            return $ensie;
        }
    }
}
