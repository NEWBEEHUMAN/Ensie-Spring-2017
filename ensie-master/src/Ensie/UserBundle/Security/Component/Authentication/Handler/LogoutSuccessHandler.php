<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 10-8-14
 * Time: 20:56
 */

namespace Ensie\UserBundle\Security\Component\Authentication\Handler;


use BeSimple\I18nRoutingBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface {

    /**
     * @var \BeSimple\I18nRoutingBundle\Routing\Router
     */
    protected $router;
    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    public function __construct(Router $router, Session $session)
    {
        $this->router = $router;
        $this->session = $session;
    }

    /**
     * Creates a Response object to send upon a successful logout.
     *
     * @param Request $request
     *
     * @return Response never null
     */
    public function onLogoutSuccess(Request $request)
    {
        $this->session->getFlashBag()->add(
            'succes',
            'flashmessage.user_succes_logout'
        );
        $url = $this->router->generate('home');
        $response = new RedirectResponse($url);
        return $response;
    }
} 