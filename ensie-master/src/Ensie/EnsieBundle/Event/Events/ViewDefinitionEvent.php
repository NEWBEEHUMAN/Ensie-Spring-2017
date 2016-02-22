<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:05
 */

namespace Ensie\EnsieBundle\Event\Events;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\EventDispatcher\Event;


class ViewDefinitionEvent extends Event
{
    protected $definition;
    protected $ensieUser;

    /**
     * @param Definition $definition
     * @param EnsieUser $ensieUser
     */
    public function __construct(Definition $definition, EnsieUser $ensieUser = null)
    {
        $this->definition = $definition;
        $this->ensieUser = $ensieUser;
    }

    /**
     * @return Definition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @param \Ensie\UserBundle\Entity\EnsieUser $ensieUser
     */
    public function setEnsieUser($ensieUser)
    {
        $this->ensieUser = $ensieUser;
    }

    /**
     * @return \Ensie\UserBundle\Entity\EnsieUser
     */
    public function getEnsieUser()
    {
        return $this->ensieUser;
    }

  }