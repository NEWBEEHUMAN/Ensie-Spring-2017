<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 31-5-14
 * Time: 15:49
 */

namespace Ensie\NotificationBundle\Notification;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Ensie\NotificationBundle\Entity\Notification;
use Ensie\NotificationBundle\Entity\NotificationRepository;
use Ensie\NotificationBundle\Entity\NotificationTemplate;
use Ensie\NotificationBundle\Entity\NotificationTemplateRepository;
use Ensie\NotificationBundle\Entity\ViewDateInterface;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NotificationService {

    /** @var  NotificationTemplateRepository */
    private $notificationTemplateRepository;

    /** @var  NotificationRepository */
    private $notificationRepository;

    /** @var  ContainerInterface */
    private $twigContainer;

    function __construct(NotificationRepository $notificationRepository, NotificationTemplateRepository $notificationTemplateRepository, ContainerInterface $twigContainer)
    {
        $this->notificationRepository = $notificationRepository;
        $this->notificationTemplateRepository = $notificationTemplateRepository;
        //We inject the container else we get a Circular reference detected exception
        $this->twigContainer = $twigContainer;
    }

    /**
     * @param EnsieUser $ensieUser
     * @param $notificationTemplateIdentifier
     * @param array $templateData
     * @param EnsieUser $relationUser
     * @return Notification|null
     */
    public function createNotification(EnsieUser $ensieUser, $notificationTemplateIdentifier, array $templateData, EnsieUser $relationUser = null)
    {
        $userLocale = $ensieUser->getLanguage()->getLocale();
        if($this->validateTemplateData($notificationTemplateIdentifier, $templateData)){
            $notificationTemplateTranslation = $this->notificationTemplateRepository->findByIdentifierAndLocale($notificationTemplateIdentifier, $userLocale);
            $notificationTemplateText = $notificationTemplateTranslation->getTemplate();
            $notificationText = $this->renderNotificationTemplate($notificationTemplateText, $templateData);
            return $this->notificationRepository->create($ensieUser, $notificationText, $notificationTemplateIdentifier, $relationUser);
        }
        return null;
    }

    /**
     * @param array $objects
     * @return array
     * @throws NoViewDateInterfaceException
     */
    public function setViewDate(array $objects)
    {
        foreach($objects as $object){
            if(!method_exists($object, 'setViewdate')){
                throw new NoViewDateInterfaceException('Object is not a instanceof ViewDateInterface');
            }
            /** @var ViewDateInterface $object */
            $object->setViewdate(new \DateTime("now"));
        }
        return $objects;
    }

    /**
     * @param EnsieUser $ensieUser
     * @param null $lastViewedNotification
     * @param null $limit
     * @return array
     */
    public function getUnviewedNotifications(EnsieUser $ensieUser = null, $lastViewedNotification = null, $limit = null) {
        return $this->notificationRepository->getUnviewedNotifications($ensieUser, $limit);
    }

    /**
     * @param EnsieUser $ensieUser
     * @return array
     */
    public function hasUnviewedNotification(EnsieUser $ensieUser)
    {
        $result = $this->getUnviewedNotifications($ensieUser);
        if(count($result) > 0){
            return true;
        }
        return false;
    }

    /**
     * @param array $notifications
     */
    public function viewNotifications(array $notifications){
        if(!empty($notifications)){
            /** @var $notification Notification */
            foreach($notifications as $notification){
                if(!$notification->getViewed())
                {
                    $notification->setViewed(true);
                    $notification->setViewDate(new \DateTime());
                }
            }
        }
    }

    /**
     * @param $notificationTemplateIdentifier
     * @param $templateData
     * @return bool
     * @throws NotificationTemplateDataNotFoundException
     */
    private function validateTemplateData($notificationTemplateIdentifier, $templateData) {
        $requiredTemplateData = NotificationTemplate::$REQUIRED_TEMPLATE_DATA[$notificationTemplateIdentifier];
        foreach($requiredTemplateData as $parameterName){
            if(!isset($templateData[$parameterName])){
                throw new NotificationTemplateDataNotFoundException('Template data '. $parameterName . ' not found for notification template: ' .$notificationTemplateIdentifier);
            }
        }
        return true;
    }

    /**
     * @param $notificationTemplateText
     * @param array $templateData
     * @return mixed
     */
    private function renderNotificationTemplate($notificationTemplateText, array $templateData){
        return $this->twigContainer->get('twig')->render($notificationTemplateText, $templateData);
    }


}