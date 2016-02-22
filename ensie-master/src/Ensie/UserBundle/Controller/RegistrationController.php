<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 22-7-14
 * Time: 21:02
 */

namespace Ensie\UserBundle\Controller;

use Ensie\SubscriptionBundle\Entity\SubscriptionRepository;
use Ensie\UserBundle\Entity\EnsieUser;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RegistrationController extends BaseController
{
    use GetCurrentLanguageTrait;

    public function registerAction(Request $request)
    {
        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->get('fos_user.registration.form.factory');
        /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        /** @var EnsieUser $user */
        $user = $userManager->createUser();
        $user->setEnabled(true);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form = $formFactory->createForm();
        $form->setData($user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $event = new FormEvent($form, $request);
            //We had to overwrite the FOSRegisterAction because I needed the user in the database first
            $user->setLanguage($this->getCurrentLanguage());
            //ToDo move to the usermanager
            $user->setUsername($user->getEmail());
            $userManager->updateUser($user);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);


            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return $this->render('FOSUserBundle:Registration:register.html.twig', array(
            'form' => $form->createView(),
        ));    }

    /**
     * @Template()
     */
    public function registerCompanyAction(Request $request)
    {
        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = $this->getDoctrine()->getManager()->getRepository('Ensie\SubscriptionBundle\Entity\Subscription');
        $companySubscriptions = $subscriptionRepository->getCompanySubscriptions();
        return array('companySubscriptions' => $companySubscriptions);
    }

    /**
     * @Template()
     */
    public function registerEducationAction(Request $request)
    {
        /** @var SubscriptionRepository $subscriptionRepository */
        $subscriptionRepository = $this->getDoctrine()->getManager()->getRepository('Ensie\SubscriptionBundle\Entity\Subscription');
        $companySubscriptions = $subscriptionRepository->getCompanySubscriptions();
        return array('companySubscriptions' => $companySubscriptions);
    }

    public function checkEmailAction()
    {
        return parent::checkEmailAction();
    }

    public function confirmAction(Request $request, $token)
    {
        try{
            return parent::confirmAction($request, $token);
        } catch (NotFoundHttpException $e){
            /** @var FlashBag $flashBag */
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('error', 'registration.confirmed_user_not_found');
            return new RedirectResponse($this->container->get('router')->generate('home'));
        }
    }

    public function confirmedAction()
    {
        /** @var FlashBag $flashBag */
        $flashBag = $this->get('session')->getFlashBag();
        $flashBag->add('succes', 'registration.confirmed');
        $user = $this->getUser();

        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return new RedirectResponse($this->container->get('router')->generate('profile_profile_edit'));
    }


}