<?php

namespace Ensie\NotificationBundle\Controller;

use Ensie\NotificationBundle\Entity\NotificationTemplate;
use Ensie\NotificationBundle\Entity\NotificationTemplateRepository;
use Ensie\NotificationBundle\Notification\NotificationService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class NotificationTemplateController extends Controller
{

    /**
     * @Route("/notification-template/install")
     * @Template()
     */
    public function installAction()
    {
        $basicNotificationTemplate = array();
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_ACCEPTED]['nl']['title'] = 'Geaccepteerd';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_ACCEPTED]['nl']['template'] = 'Je artikel <a href="LINK NAAR ARTIKEL">{{ definition.word }}</a> is toegevoegd';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_ACCEPTED]['en']['title'] = 'Accepted';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_ACCEPTED]['en']['template'] = 'Your aricle <a href="LINK NAAR ARTIKEL">{{ definition.word }}</a> has been accepted';

        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_REFUSED]['nl']['title'] = 'Geweigerd';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_REFUSED]['nl']['template'] = 'Je artikel <a href="LINK NAAR ARTIKEL">{{ definition.word }}</a> is geweigerd';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_REFUSED]['en']['title'] = 'Refused';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_REFUSED]['en']['template'] = 'Your article <a href="LINK NAAR ARTIKEL">{{ definition.word }}</a> has been refused';

        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_FAVORITE]['nl']['title'] = 'Favoriet';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_FAVORITE]['nl']['template'] = '<a href="LINK NAAR USER">{{ user.name }}</a> heeft je aan zijn favorieten toegevoegd';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_FAVORITE]['en']['title'] = 'Favorite';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_FAVORITE]['en']['template'] = '<a href="LINK NAAR USER">{{ user.name }}</a> added you to his favorites</a> ';

        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_RATED]['nl']['title'] = 'Artikel gewaardeerd';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_RATED]['nl']['template'] = 'Je artikel <a href="LINK NAAR ARTIKEL">{{ definition.word }}</a> is beoordeeld met {{ rating.rating }} sterren';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_RATED]['en']['title'] = 'Article rated';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_RATED]['en']['template'] = 'Your article <a href="LINK NAAR ARTIKEL">{{ definition.word }}</a> has been rated with {{ rating.rating }} stars';

        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_RATED_FEEDBACK]['nl']['title'] = 'Artikel gewaardeer met feedback';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_RATED_FEEDBACK]['nl']['template'] = 'Je artikel <a href="LINK NAAR ARTIKEL">{{ definition.word }}</a> is beoordeeld met {{ rating.rating }} sterren en feedback: {{ rating.comment }}';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_RATED_FEEDBACK]['en']['title'] = 'Article rated with feedback';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_RATED_FEEDBACK]['en']['template'] = 'Your article <a href="LINK NAAR ARTIKEL">{{ definition.word }}</a> has been rated with {{ rating.rating }} stars and feedback: {{ rating.comment }}';

        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_MESSAGE_RECEIVED]['nl']['title'] = 'Bericht ontvangen';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_MESSAGE_RECEIVED]['nl']['template'] = 'Je hebt een <a href="LINK NAAR BERICHTEN">bericht</a> ontvangen';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_MESSAGE_RECEIVED]['en']['title'] = 'Message received';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_MESSAGE_RECEIVED]['en']['template'] = 'You recieved a message <a href="LINK NAAR BERICHTEN">bericht</a>';

        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_BADGE_RECEIVED]['nl']['title'] = 'Badge ontvangen';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_BADGE_RECEIVED]['nl']['template'] = 'Je hebt de badge {{ badge.name }} ontvangen';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_BADGE_RECEIVED]['en']['title'] = 'Badge';
        $basicNotificationTemplate[NotificationTemplate::IDENTIFIER_BADGE_RECEIVED]['en']['template'] = 'You received the {{ badge.name }} badge';

        $em = $this->getDoctrine()->getManager();
        /** @var NotificationTemplateRepository $notificationTemplateRepository */
        $notificationTemplateRepository = $em->getRepository('EnsieNotificationBundle:NotificationTemplate');
        foreach($basicNotificationTemplate as $identifier => $templates)
        {
            foreach($templates as $locale => $templateValues){
                $notificationTemplateRepository->create($identifier, $templateValues['title'], $templateValues['template'], $locale);
            }
        }
        $em->flush();
        return $this->redirect($this->generateUrl('ensie_notification_notificationtemplate_showall'));
    }

    /**
     * @Route("/notification-template/show-all")
     * @Template()
     */
    public function showAllAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var NotificationTemplateRepository $notificationTemplateRepository */
        $notificationTemplateRepository = $em->getRepository('EnsieNotificationBundle:NotificationTemplate');
        $notificationTemplates = $notificationTemplateRepository->findAll();
        return array('result' => $notificationTemplates);
    }

}
