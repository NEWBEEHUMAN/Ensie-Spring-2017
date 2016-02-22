<?php

namespace Ensie\NotificationBundle\Controller;

use Ensie\NotificationBundle\Entity\NotificationTemplate;
use Ensie\NotificationBundle\Entity\NotificationTemplateRepository;
use Ensie\NotificationBundle\Notification\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{

    /**
     * @Route("/notification/test")
     * @Template()
     */
    public function testAction()
    {
//        /** @var NotificationTemplateRepository $notificationTemplateRepository */
//        $notificationTemplateRepository = $em->getRepository('EnsieNotificationBundle:NotificationTemplate');
//
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_ACCEPTED, 'nl'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_BADGE_RECEIVED, 'nl'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_FAVORITE, 'nl'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_MESSAGE_RECEIVED, 'nl'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_RATED, 'nl'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_RATED_FEEDBACK, 'nl'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_MESSAGE_RECEIVED, 'nl'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_REFUSED, 'nl'));
//
//        echo '<br><br>';
//        $em->clear();
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_ACCEPTED, 'en'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_BADGE_RECEIVED, 'en'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_FAVORITE, 'en'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_MESSAGE_RECEIVED, 'en'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_RATED, 'en'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_RATED_FEEDBACK, 'en'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_MESSAGE_RECEIVED, 'en'));
//        var_dump($notificationTemplateRepository->findByIdentifier(NotificationTemplate::IDENTIFIER_REFUSED, 'en'));
//
//        echo '<br><br>';

        $em = $this->getDoctrine()->getManager();
        /** @var NotificationService $notificationService */
        $notificationService = $this->container->get('ensie.notification_service');
        $notificationService->createNotification(null, NotificationTemplate::IDENTIFIER_MESSAGE_RECEIVED, array());
        $em->flush();
        $notifications = $notificationService->getUnviewedNotifications();
        var_dump($notifications);
        return array('notifications' => $notifications);
    }

    /**
     * @Route("/notification/view/{id}", requirements={"id" = "\d+"})
     * @Template()
     */
    public function test2Action($id)
    {
        $em = $this->getDoctrine()->getManager();
        /** @var NotificationService $notificationService */
        $notificationService = $this->container->get('ensie.notification_service');
        $notifications = $notificationService->getUnviewedNotifications(null, $id, null);
        $notificationService->setViewDate($notifications);
        $em->flush();
    }

}
