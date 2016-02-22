<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 16-6-14
 * Time: 12:15
 */

namespace Ensie\NotificationBundle\Service\Twig;

use Ensie\NotificationBundle\Notification\NotificationService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Service\FriendService;

class HasNotifications extends \Twig_Extension
{

    /**
     * @var FriendService
     */
    private $notificationService;

    function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('hasNotifications', array($this, 'hasNotifications')),
        );
    }

    /**
     * @param $ensieUser
     * @return array|bool
     */
    public function hasNotifications($ensieUser)
    {
        if($ensieUser instanceof EnsieUser){
            return $this->notificationService->hasUnviewedNotification($ensieUser);
        }
        return false;
    }

    public function getName()
    {
        return 'ensie_notification_has_notifications';
    }
}