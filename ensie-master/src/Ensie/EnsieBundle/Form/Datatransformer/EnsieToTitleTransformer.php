<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 14-7-14
 * Time: 16:49
 */

namespace Ensie\EnsieBundle\Form\Datatransformer;


use Ensie\EnsieBundle\Entity\Ensie;
use Ensie\EnsieBundle\Entity\EnsieRepository;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Form\DataTransformerInterface;


class EnsieToTitleTransformer implements DataTransformerInterface {

    /**
     * @var EnsieRepository
     */
    private $ensieRepository;

    /** @var EnsieUser */
    private $ensieUser;

    /** @var Language */
    private $language;

    function __construct(EnsieRepository $ensieRepository, EnsieUser $ensieUser, Language $language)
    {
        $this->ensieRepository = $ensieRepository;
        $this->ensieUser = $ensieUser;
        $this->language = $language;
    }

    /**
     * @param mixed $ensie
     * @return mixed|string
     */
    public function transform($ensie)
    {
        /** @var Ensie $ensie*/
        if (null === $ensie) {
            return "";
        }
        return $ensie->getTitle();
    }

    /**
     * @param mixed $title
     * @return Ensie|null
     */
    public function reverseTransform($title)
    {
        if($title instanceof Ensie){
            return $title;
        }
        if(is_string($title)){
            return $this->ensieRepository->giveEnsieTitleAndUser($title, $this->ensieUser, $this->language);
        }
        return null;
    }
}