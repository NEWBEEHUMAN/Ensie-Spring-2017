<?php

namespace Ensie\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * NotificationTemplate
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ensie\NotificationBundle\Entity\NotificationTemplateRepository")
 */
class NotificationTemplate
{
    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\Translatable\Translatable;

    const IDENTIFIER_ACCEPTED = 'accepted';
    const IDENTIFIER_REJECTED = 'rejected';
    const IDENTIFIER_FAVORITE = 'favorite';
    const IDENTIFIER_RATED = 'rated';
    const IDENTIFIER_RATED_FEEDBACK = 'rated_with_feedback';
    const IDENTIFIER_MESSAGE_RECEIVED = 'message';
    const IDENTIFIER_DEFINITION_ADDED_BY_FAVORITE = 'added_by_favorite';
    const IDENTIFIER_BADGE_RECEIVED = 'badge_recieved';

    public static $REQUIRED_TEMPLATE_DATA = array(
        self::IDENTIFIER_ACCEPTED => array('definition', 'feedback'),
        self::IDENTIFIER_REJECTED => array('definition', 'feedback'),
        self::IDENTIFIER_FAVORITE => array('ensieUser'),
        self::IDENTIFIER_RATED => array('rating'),
        self::IDENTIFIER_RATED_FEEDBACK => array('rating'),
        self::IDENTIFIER_MESSAGE_RECEIVED => array(),
        self::IDENTIFIER_DEFINITION_ADDED_BY_FAVORITE => array('definition'),
        self::IDENTIFIER_BADGE_RECEIVED => array('badge')
    );

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=255)
     */
    private $identifier;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return NotificationTemplate
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return array
     */
    public function getTemplateDataArray()
    {
        return self::$REQUIRED_TEMPLATE_DATA;
    }

}
