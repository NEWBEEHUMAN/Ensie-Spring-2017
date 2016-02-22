<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 12-6-14
 * Time: 10:52
 */

namespace Ensie\SimpleTextBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class SimpleTextTranslation
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $simpleText;

    /**
     * @return string
     */
    public function getSimpleText()
    {
        return $this->simpleText;
    }

    /**
     * @param  string
     * @return null
     */
    public function setSimpleText($simpleText)
    {
        $this->simpleText = $simpleText;
    }
} 