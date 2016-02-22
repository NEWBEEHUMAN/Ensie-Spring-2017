<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 21-7-14
 * Time: 21:17
 */

namespace Ensie\LanguageBundle\Service\Twig;


use Ensie\LanguageBundle\Entity\Language;
use Ensie\LanguageBundle\Service\LanguageService;

class LanguageExtension extends \Twig_Extension
{
    /** @var  LanguageService */
    private $languageService;

    function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function getGlobals()
    {
        return array(
            'language' => $this->languageService->getCurrentLanguage(),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('currencySymbol', array($this, 'currencySymbolFunction')),
        );
    }

    public function currencySymbolFunction(Language $language) {
        switch ($language->getLocale()){
            case 'nl':
                return '€';
            default:
                return '$';
        }
    }

    public function getName()
    {
        return 'language_extension';
    }
}