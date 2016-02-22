<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 25-10-14
 * Time: 11:34
 */

namespace Ensie\EnsieBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Ensie\LanguageBundle\Entity\Language;

class PopularDefinitionRepository extends EntityRepository{

    /**
     * @param Language $language
     * @param int $limit
     * @return array
     */
    public function getPopularDefinitions(Language $language, $limit)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('popularDefinition');
        $qb->from('Ensie\EnsieBundle\Entity\PopularDefinition', 'popularDefinition');
        $qb->where('popularDefinition.language = :language');
        $qb->addOrderBy('popularDefinition.language', 'DESC');
        $qb->addOrderBy('popularDefinition.position', 'ASC');
        $qb->setMaxResults($limit);
        $qb->setParameter('language', $language);
        return $qb->getQuery()->getResult();
    }
} 