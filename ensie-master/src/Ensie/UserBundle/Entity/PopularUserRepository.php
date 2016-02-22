<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:41
 */

namespace Ensie\UserBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Ensie\LanguageBundle\Entity\Language;

class PopularUserRepository extends EntityRepository{

    /**
     * @param Language $language
     * @param int $limit
     * @return array
     */
    public function getPopularUsersRepository(Language $language, $limit)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('popularuser');
        $qb->from('Ensie\UserBundle\Entity\PopularUser', 'popularuser');
        $qb->where('popularuser.language = :language');
        $qb->addOrderBy('popularuser.language', 'DESC');
        $qb->addOrderBy('popularuser.position', 'ASC');
        $qb->setMaxResults($limit);
        $qb->setParameter('language', $language);
        return $qb->getQuery()->getResult();
    }
} 