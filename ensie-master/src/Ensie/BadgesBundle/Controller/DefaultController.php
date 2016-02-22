<?php

namespace Ensie\BadgesBundle\Controller;

use Ensie\BadgesBundle\Entity\Badge;
use Gedmo\Translatable\TranslatableListener;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/badges/test")
     * @Template()
     */
    public function testAction()
    {
        /** @var TranslatableListener $translatableListener */
        $translatableListener = $this->get('gedmo_translatable');
        echo $translatableListener->getDefaultLocale();
        //die;
        $em = $this->getDoctrine()->getManager();
        $rp = $em->getRepository('EnsieBadgesBundle:Badge');
//        /** @var Badge $badge */
        $badge = new Badge();//$rp->findOneBy(array('identifier' => '100'));
//        //$badge->setTranslatableLocale('de_de');
//        //$em->refresh($badge);
        //$badge = new Badge();
        $badge->setIdentifier('200');
        $badge->setName('2 Honderd NL');
        $badge->setDescription('200 berichten Ja!!');
        //$badge->setTranslatableLocale('de_de');

        $em->persist($badge);
        $em->flush();

        die;
    }
}
