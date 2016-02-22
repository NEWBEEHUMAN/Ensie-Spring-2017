<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 8-8-14
 * Time: 12:28
 */

namespace Ensie\StatsBundle\Service;


use Doctrine\ORM\EntityManagerInterface;

class AbstractStatsService {

    /** @var  EntityManagerInterface */
    protected $entityManager;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
} 