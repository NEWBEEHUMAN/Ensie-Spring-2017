<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-4-14
 * Time: 21:22
 */

namespace Ensie\SubscriptionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * @ORM\Entity
 */
class SubscriptionTranslation {

    use ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $title = '';

    /**
     * @ORM\Column(type="text")
     */
    private $description = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $price = '';

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }
}