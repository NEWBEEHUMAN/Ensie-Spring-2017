<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-11-14
 * Time: 11:51
 */

namespace Ensie\EnsieBundle\Service;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Entity\ViewRepository;

class DefinitionService {

    private $viewRepository;
    private $definitionRepository;
    private $em;

    public function __construct(ViewRepository $viewRepository, DefinitionRepository $definitionRepository, EntityManagerInterface $em)
    {
        $this->viewRepository = $viewRepository;
        $this->definitionRepository = $definitionRepository;
        $this->em = $em;
    }

    public function updateStatsDefinition(Definition $definition){
        $definition->setViewCount($this->viewRepository->countViews($definition) + $definition->getStartViewCount());
        $this->em->flush();
    }

    public function updateStatsDefinitionArray($definitions){
        /** @var $definition Definition */
        foreach($definitions as $definition){
            $this->updateStatsDefinition($definition);
        }
    }
} 