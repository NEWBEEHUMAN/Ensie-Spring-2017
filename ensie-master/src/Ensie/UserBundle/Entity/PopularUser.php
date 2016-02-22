<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:22
 */

namespace Ensie\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ensie\LanguageBundle\Entity\Language;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="Ensie\UserBundle\Entity\PopularUserRepository")
 * @ORM\Table()
 */
class PopularUser {

    use ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var EnsieUser
     *
     * @ORM\ManyToOne(targetEntity="Ensie\UserBundle\Entity\EnsieUser")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $ensieUser;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="Ensie\LanguageBundle\Entity\Language", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $language;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", length=9)
     */
    private $position;

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
     * @param int $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return int
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }
}