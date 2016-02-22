<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 18-9-14
 * Time: 12:20
 */

namespace Ensie\MailerBundle\Service;


use Symfony\Bundle\TwigBundle\TwigEngine;

class TwigTemplateUrlTranslator implements TemplateUrlTranslatorInterface {

    /** @var \Twig_Environment  */
    protected $twigEngine;
    protected $locale;

    function __construct(TwigEngine $twigEngine)
    {
        $this->twigEngine = $twigEngine;
    }

    function setLocale($locale){
        $this->locale = $locale;
    }

    function translate($templateUrl)
    {
        if(!isset($this->locale)){
            throw new \Exception('Locale not found for Twig template url transformer');
        }
        $url = str_replace('.html.twig', '.' . $this->locale . '.html.twig', $templateUrl);
        if(!$this->twigEngine->exists($url)){
            return $templateUrl;
        }else{
            return $url;
        }
    }
} 