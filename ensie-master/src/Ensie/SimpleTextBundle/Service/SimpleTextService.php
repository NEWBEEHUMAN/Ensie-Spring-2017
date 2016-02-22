<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 11-6-14
 * Time: 15:53
 */

namespace Ensie\SimpleTextBundle\Service;


use Ensie\SimpleTextBundle\Entity\SimpleTextRepository;

class SimpleTextService {


    /** @var  SimpleTextRepository */
    private $simpleTextRepository;

    private $locale;

    /**
     * @param $simpleTextRepository
     * @param $locale
     */
    function __construct(SimpleTextRepository $simpleTextRepository, $locale)
    {
        $this->simpleTextRepository = $simpleTextRepository;
        $this->locale = $locale;
    }


    public function getSimpleText($identifier){
        $this->simpleTextRepository->findBy(array('identifier', $identifier));
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }


} 