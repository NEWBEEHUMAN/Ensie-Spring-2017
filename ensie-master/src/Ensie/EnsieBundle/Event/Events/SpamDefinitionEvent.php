<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 28-5-14
 * Time: 22:05
 */

namespace Ensie\EnsieBundle\Event\Events;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\Spam;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\EventDispatcher\Event;


class SpamDefinitionEvent extends Event
{
    protected $definition;
    protected $ensieUser;
    protected $spam;

    /**
     * @param Definition $definition
     * @param EnsieUser $ensieUser
     * @param Spam $spam
     */
    public function __construct(Definition $definition, EnsieUser $ensieUser, Spam $spam)
    {
        $this->definition = $definition;
        $this->ensieUser = $ensieUser;
        $this->spam = $spam;
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

    /**
     * @param \Ensie\EnsieBundle\Entity\Spam $spam
     */
    public function setSpam($spam)
    {
        $this->spam = $spam;
    }

    /**
     * @return \Ensie\EnsieBundle\Entity\Spam
     */
    public function getSpam()
    {
        return $this->spam;
    }
}