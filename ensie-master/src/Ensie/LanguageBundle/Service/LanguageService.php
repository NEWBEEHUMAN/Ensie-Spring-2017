<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 20-6-14
 * Time: 13:19
 */

namespace Ensie\LanguageBundle\Service;


use Ensie\LanguageBundle\Entity\Language;
use Ensie\LanguageBundle\Entity\LanguageRepository;
use Symfony\Component\HttpFoundation\Session\Session;

class LanguageService {

    /** @var Session */
    private $session;

    /** @var  LanguageRepository */
    private $languageRepository;

    /** @var Language */
    private $currentLanguage;

    function __construct($languageRepository, $session)
    {
        $this->languageRepository = $languageRepository;
        $this->session = $session;
    }

    /**
     * @return Language|null
     */
    public function getCurrentLanguage(){
        if(isset($this->currentLanguage)){
            return $this->currentLanguage;
        }
        $locale = $this->session->get('_locale'); //Set in the LocaleListener
        $language = $this->languageRepository->getByLocale($locale);
        if(empty($language)){
            $language = $this->languageRepository->getByLocale('en');
        }
        $this->currentLanguage = $language;
        return $this->currentLanguage;
    }
} 