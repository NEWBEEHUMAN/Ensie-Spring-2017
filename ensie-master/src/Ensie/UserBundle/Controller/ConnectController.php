<?php

namespace Ensie\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use HWI\Bundle\OAuthBundle\Controller\ConnectController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ConnectController extends BaseController
{
    /**
     * @Route("/show")
     * @Template()
     */
    public function showAction(Request $request)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        var_dump($user);
        return array('user' => $user);
    }
}
