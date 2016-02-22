<?php
/**
 * Created by PhpStorm.
 * User: vladyslav
 * Date: 01.05.15
 * Time: 11:58
 */

namespace Ensie\WallOfFameBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class WallOfFameController extends Controller{

    /**
     * @Route("/walloffame", name="walloframe")
     * @Template()
     */
    public function wallOfFameAction(Request $request)
    {
        $request = $this->get('request');
        $locale = $request->getLocale();
        $queryService = $this->get('wall_of_fame.queries_service');
        $users = $queryService->getUsersForWallOfFameByLocale($locale);

        return array('users'=>$users);
    }

    /**
     * @Route("/walloffame/lastmonth", name="walloframe_lastmonth")
     * @Template("EnsieWallOfFameBundle:WallOfFame:wallOfFame.html.twig")
     */
    public function wallOfFameLastMonthAction(Request $request)
    {
        $request = $this->get('request');
        $locale = $request->getLocale();
        $queryService = $this->get('wall_of_fame.queries_service');
        $users = $queryService->getUsersForWallOfFameByLocaleInLastMonth($locale);

        return array('users'=>$users);
    }

}