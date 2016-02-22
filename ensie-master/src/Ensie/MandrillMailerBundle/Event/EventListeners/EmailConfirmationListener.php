<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 14-8-14
 * Time: 11:54
 */

namespace Ensie\MandrillMailerBundle\Event\EventListeners;

use Ensie\MandrillMailerBundle\Service\MandrillMailSender;
use Ensie\UserBundle\Entity\EnsieUser;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EmailConfirmationListener implements EventSubscriberInterface
{
    private $mandrillMailSender;
    private $tokenGenerator;
    private $router;
    private $session;
    private $salesEmail;

    /**
     * @param $salesEmail
     * @param MandrillMailSender $mandrillMailSender
     * @param TokenGeneratorInterface $tokenGenerator
     * @param UrlGeneratorInterface $router
     * @param SessionInterface $session
     */
    public function __construct($salesEmail, MandrillMailSender $mandrillMailSender, TokenGeneratorInterface $tokenGenerator, UrlGeneratorInterface $router, SessionInterface $session)
    {
        $this->mandrillMailSender = $mandrillMailSender;
        $this->tokenGenerator = $tokenGenerator;
        $this->router = $router;
        $this->session = $session;
        $this->salesEmail = $salesEmail;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess',
        );
    }

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        /** @var $user EnsieUser */
        $user = $event->getForm()->getData();

        $user->setEnabled(false);

        if (null === $user->getConfirmationToken()) {
            $user->setConfirmationToken($this->tokenGenerator->generateToken());
        }

        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);

        $this->mandrillMailSender->send($user, 'registration', 'register_company', array(
            'ACTIVATIONURL' => $url,
            'SUBSCRIPTION' => $user->getSubscription()->translate()->getTitle(),
            'PRICE' => $user->getSubscription()->translate()->getPrice()
        ));

        $this->mandrillMailSender->send($user, 'registration', 'ensie_register_company', array(
                'ACTIVATIONURL' => $url,
                'COMPANYNAME' => $user->getCompanyName(),
                'EMAIL' => $user->getEmail(),
                'SUBSCRIPTION' => $user->getSubscription()->translate()->getTitle(),
                'PRICE' => $user->getSubscription()->translate()->getPrice(),
            ),
            $this->salesEmail);

        $this->session->set('fos_user_send_confirmation_email/email', $user->getEmail());
        $url = $this->router->generate('fos_user_registration_check_email');
        $event->setResponse(new RedirectResponse($url));
    }
}