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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {

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
     * @param Request $request
     * @param TokenInterface $token
     * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $this->session->getFlashBag()->add(
            'succes',
            'flashmessage.user_succes_login_%name%'
        );
        // redirect the user to where they were before the login process begun.
        $referer_url = $request->headers->get('referer');
        if($referer_url){
            $response = new RedirectResponse($referer_url);
        } else {
            $response = new RedirectResponse($this->router->generate('home'));
        }
        return $response;
    }

} 