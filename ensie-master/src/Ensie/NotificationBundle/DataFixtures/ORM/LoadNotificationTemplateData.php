<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 8-6-14
 * Time: 12:49
 */

namespace Ensie\NotificationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Ensie\NotificationBundle\Entity\NotificationTemplate;
use Ensie\NotificationBundle\Entity\NotificationTemplateRepository;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadNotificationTemplateData implements FixtureInterface, ContainerAwareInterface{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $em
     */
    function load(ObjectManager $em)
    {
        $defaultLocale = $this->container->getParameter('locale');
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

        /** @var NotificationTemplateRepository $notificationTemplateRepository */
        $notificationTemplateRepository = $em->getRepository('EnsieNotificationBundle:NotificationTemplate');
        foreach($basicNotificationTemplate as $identifier => $templates)
        {
            /** @var NotificationTemplate $notificationTemplate */
            $notificationTemplate = $notificationTemplateRepository->findOneBy(array('identifier' => $identifier));
            if(!$notificationTemplate){
                $notificationTemplate = $notificationTemplateRepository->create($identifier, $defaultLocale, $templates[$defaultLocale]['title'], $templates[$defaultLocale]['template']);
            }
            foreach($templates as $locale => $templateValues){

                $notificationTemplateRepository->update($notificationTemplate, $locale, $templateValues['title'], $templateValues['template']);
            }
        }
        $em->flush();
    }


} 