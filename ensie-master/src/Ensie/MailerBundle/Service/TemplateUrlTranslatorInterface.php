<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 18-9-14
 * Time: 12:19
 */

namespace Ensie\MailerBundle\Service;


interface TemplateUrlTranslatorInterface {

    function setLocale($locale);
    function translate($templateUrl);
} 