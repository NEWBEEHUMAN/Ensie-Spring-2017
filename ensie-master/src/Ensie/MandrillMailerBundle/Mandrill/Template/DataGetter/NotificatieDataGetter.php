<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 9-11-14
 * Time: 11:00
 */

namespace Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter;


use Ensie\StatsBundle\Service\EmailNotificationService;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Bundle\TwigBundle\TwigEngine;

class NotificatieDataGetter extends AbstractDataGetter{

    /** @var  EmailNotificationService */
    protected $emailNotificationService;
    protected $twig;

    /** @var  EnsieUser */
    protected $ensieUser;

    /**
     * @param EmailNotificationService $emailNotificationService
     * @param \Twig_Environment $twig
     */
    function __construct(EmailNotificationService $emailNotificationService, \Twig_Environment $twig)
    {
        $this->emailNotificationService = $emailNotificationService;
        $this->twig = $twig;
    }

    /**
     * @param EnsieUser $ensieUser
     */
    public function setUser(EnsieUser $ensieUser){
        $this->ensieUser = $ensieUser;
    }

    /**
     * @return array
     */
    public function gatherData()
    {
        $statsData = $this->emailNotificationService->getStats($this->ensieUser);
        $statsData['FOLLOWERS'] = $this->convertFollowersToHtml($statsData['FOLLOWERS']);
        $statsData['NEWDEFINITIONS'] = $this->convertDefinitionUpdateToHtml($statsData['NEWDEFINITIONS']);
        $statsData['OWNDEFINITIONRATING'] = $this->convertOwnDefinitionRatingsToHtml($statsData['OWNDEFINITIONRATING']);
        return array_merge($statsData, array('FORMATTEDNAME' => $this->ensieUser->getFormattedName()));
    }

    /**
     * @throws \Exception
     */
    public function validate()
    {
        if(!isset($this->ensieUser) or empty($this->ensieUser)){
            throw new \Exception('User is not set. Use the setter ');
        }
    }

    /**
     * @param $followers
     * @return string
     */
    public function convertFollowersToHtml($followers){
        $result = '';
        if(is_array($followers) and count($followers) > 0){
            $result = $this->twig->render('EnsieMandrillMailerBundle:Stats:followers.html.twig', array('followers' => $followers));
            $this->containsData = true;
        }
        return $result;
    }

    /**
     * @param $definitionUpdates
     * @return string
     */
    public function convertDefinitionUpdateToHtml($definitionUpdates){
        $result = '';
        if(is_array($definitionUpdates) and count($definitionUpdates) > 0){
            $result = $this->twig->render('EnsieMandrillMailerBundle:Stats:definitionUpdates.html.twig', array('definitionUpdates' => $definitionUpdates, 'locale' => $this->ensieUser->getLocale()));
            $this->containsData = true;
        }
        return $result;
    }

    /**
     * @param $definitions
     * @return string
     */
    public function convertOwnDefinitionRatingsToHtml($definitions){
        $result = '';
        if(is_array($definitions) and count($definitions) > 0){
            $result = $this->twig->render('EnsieMandrillMailerBundle:Stats:ratings.html.twig', array('definitions' => $definitions, 'locale' => $this->ensieUser->getLocale()));
            $this->containsData = true;
        }
        return $result;
    }
} 