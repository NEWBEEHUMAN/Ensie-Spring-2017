<?php

namespace Ensie\SimpleTextBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query;
use Gedmo\Translatable\TranslatableListener;

/**
 * SimpleTextRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SimpleTextRepository extends EntityRepository
{
    /**
     * @param $identifier
     * @param $text
     * @param $locale
     * @return null|object|void|SimpleText
     */
    public function create($identifier, $text, $locale){
        $simpleText = $this->findByIdentifier($identifier);
        if(!$simpleText){
            $simpleText = $this->createTranslation($identifier, $text, $locale);
        }
        $simpleText = $this->addTranslation($simpleText, $text, $locale);
        return $simpleText;
    }

    /**
     * @param $identifier
     * @param $locale
     * @return mixed
     */
    public function findByIdentifierAndLocale($identifier, $locale){
        $qb = $this->createQueryBuilder('st');
        $qb->select('st, stt');
        $qb->leftJoin('st.translations', 'stt', 'WITH', 'stt.locale = :locale');
        $qb->where('st.identifier = :identifier');
        $qb->setParameter('identifier', $identifier);
        $qb->setParameter('locale', $locale);
        $query = $qb->getQuery();
        $query->useResultCache(true, '60');
        return $query->getSingleResult();
    }

    /**
     * @return array
     */
    public function getAll(){
        $qb = $this->createQueryBuilder('simpleText');
        $qb->select('simpleText, translations');
        $qb->leftJoin('simpleText.translations', 'translations');
        return $qb->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getAllKyByIdentifier(){
        $simpleTextArray = $this->getAll();
        $result = array();
        /** @var SimpleText $simpleText */
        foreach($simpleTextArray as $simpleText){
            $result[$simpleText->getIdentifier()] = $simpleText;
        }
        return $result;
    }

    /**
     * @param $identifier
     * @return null|object
     */
    public function findByIdentifier($identifier){
        $qb = $this->createQueryBuilder('st');
        $qb->where('st.identifier = :identifier');
        $qb->setParameter('identifier', $identifier);
        $query = $qb->getQuery();
        $query->useResultCache(true, '60');
        try{
            return $query->getSingleResult();
        }
        catch(NoResultException $e){
            return '';
        }
    }

    /**
     * @param $identifier
     * @param $text
     * @param $locale
     * @return SimpleText
     */
    private function createTranslation($identifier, $text, $locale){
        $simpleText = new SimpleText();
        $simpleText->setIdentifier($identifier);
        $this->getEntityManager()->persist($simpleText);
        $this->addTranslation($simpleText, $text, $locale);
        return $simpleText;
    }

    /**
     * @param SimpleText $simpleText
     * @param $text
     * @param $locale
     * @return SimpleText
     */
    private function addTranslation(SimpleText $simpleText, $text, $locale){
        /** @var SimpleTextTranslation $translation */
        $translation = $simpleText->translate($locale);
        if($translation->getLocale() == $locale){
            $translation->setLocale($locale);
            $translation->setSimpleText($text);
        }else{
            $translation = new SimpleTextTranslation();
            $translation->setSimpleText($text);
            $translation->setLocale($locale);
            $simpleText->addTranslation($translation);
        }
        return $simpleText;
    }
}
