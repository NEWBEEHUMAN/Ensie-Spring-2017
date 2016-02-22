<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 13-8-14
 * Time: 10:16
 */

namespace Ensie\MailerBundle\Service;


use Ensie\MailerBundle\MailParameters\AbstractMailParameters;
use Swift_Message;
use Symfony\Component\Translation\TranslatorInterface;

class MailerService {

    /**
     * @var \Swift_Mailer
     */
    private $swiftMailer;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @var string
     */
    private $from;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var TemplateUrlTranslatorInterface
     */
    private $templateUrlTranslator;

    function __construct($fromEmail, \Swift_Mailer $swiftMailer, \Twig_Environment $twig, TranslatorInterface $translator)
    {
        $this->from = $fromEmail;
        $this->swiftMailer = $swiftMailer;
        $this->twig = $twig;
        $this->translator= $translator;
    }

    function setTwigTemplateUrlTranslator(TemplateUrlTranslatorInterface $templateUrlTranslator){
        $this->templateUrlTranslator = $templateUrlTranslator;
    }

    function setLocal($locale){

    }

    /**
     * @param $to
     * @param AbstractMailParameters $parameters
     * @param string $locale
     */
    function sentMail($to, AbstractMailParameters $parameters, $locale = null){
        $url = $parameters->getTemplate();
        if(isset($this->templateUrlTranslator) and $locale){
            $this->templateUrlTranslator->setLocale($locale);
            $url = $this->templateUrlTranslator->translate($url);
        }
        $body = $this->twig->render($url, $parameters->getParameters());
        $message = Swift_Message::newInstance()
            ->setSubject($this->translator->trans($parameters->getSubject(), array(), null, $locale))
            ->setFrom($this->from)
            ->setTo($to)
            ->setBody($body, 'text/html');
        $result = $this->swiftMailer->send($message);
    }
} 