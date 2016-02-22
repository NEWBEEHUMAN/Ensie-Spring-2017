<?php

namespace Ensie\WallOfFameBundle\Service;
use Doctrine\ORM\EntityManagerInterface;



/**
 * Created by PhpStorm.
 * User: vladyslav
 * Date: 01.05.15
 * Time: 21:54
 */

class AbstractService {

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

}