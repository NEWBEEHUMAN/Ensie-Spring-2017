<?php

namespace Ensie\EnsieBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ensie\LanguageBundle\Entity\Language;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends EntityRepository
{
    public function getCategoryBySlug($categorySlug)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('category');
        $qb->from('Ensie\EnsieBundle\Entity\Category', 'category');
        $qb->andWhere('category.slug = :slug');
        $qb->setParameter('slug', $categorySlug);
        return $qb->getQuery()->getResult();
    }

    public function getAll(Language $language)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('category, language');
        $qb->from('Ensie\EnsieBundle\Entity\Category', 'category');
        $qb->innerJoin('category.language', 'language', 'WITH', 'language = :language');
        $qb->setParameter('language', $language);
        return $qb->getQuery()->getResult();
    }
}
