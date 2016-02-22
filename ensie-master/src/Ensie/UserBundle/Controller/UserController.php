<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 22-7-14
 * Time: 21:02
 */

namespace Ensie\UserBundle\Controller;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Entity\EnsieRepository;
use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Ensie\MandrillMailerBundle\Service\MandrillMailerService;
use Ensie\MandrillMailerBundle\Service\MandrillMailSender;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\EnsieUserRepository;
use Ensie\UserBundle\Entity\PopularUserRepository;
use Ensie\UserBundle\Form\ContactType;
use Ensie\UserBundle\Model\Contact;
use Ensie\UserBundle\Service\FriendService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{

    use GetCurrentLanguageTrait;

    /**
     * @Template()
     * @param Request $request
     * @param $userSlug
     * @param string $definitionSlug
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Security\Core\Exception\AccessDeniedException
     */
    public function contactWriterAction(Request $request, $userSlug, $definitionSlug = ''){
        /** @var EnsieUser $ensieUser */
        if(!$ensieUser = $this->getUserBySlug($userSlug)){
            return $this->forward('EnsieUserBundle:User:userNotFound');
        }

        $contact = new Contact();

        $form = $this->createForm(new ContactType(), $contact);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var MandrillMailSender $mandrillMailSender */
            $mandrillMailSender = $this->get('mandrill_mailer.mandrill_mail_sender');
            $templateData = array(
                'DESCRIPTION' => nl2br($contact->getMessage()),
                'EMAIL' => $contact->getEmail(),
                'URL' => $contact->getUrl(),
            );
            $mandrillMailSender->send($ensieUser,'contact', 'writer', $templateData);

            //Admins addresses
            $adminAddresses = $this->container->getParameter('mail_to_info_addresses');
            foreach($adminAddresses as $mailAddress){
                $ensieUser->setEmail($mailAddress);
                $mandrillMailSender->send($ensieUser, 'contact', 'writer', $templateData);
            }
            return $this->render('EnsieUserBundle:User:contactSuccess.html.twig');
        }

        return array(
            'form' => $form->createView(),
            'ensieUser' => $ensieUser);
    }

    /**
     * @Template()
     * @param Request $request
     * @param $userSlug
     * @return array
     */
    public function detailUserAction(Request $request, $userSlug){
        if(!$ensieUser = $this->getUserBySlug($userSlug)){
            return $this->forward('EnsieUserBundle:User:userNotFound');
        }
        /** @var EnsieRepository $ensieRepository */
        $ensieRepository = $this->container->get('ensie.ensie_ensie_repository');
        $ensieList = $ensieRepository->getEnsiesByUser($ensieUser, $this->getCurrentLanguage());

        /** @var StatsService $statsService */
        $statsService = $this->get('ensie.stats.basic_stats_service');
        $ensieUserStats = $statsService->getBasicUserStats($ensieUser, $this->getCurrentLanguage());

        return array('ensieUser' => $ensieUser, 'ensieList' => $ensieList, 'ensieUserStats' => $ensieUserStats);
    }

    /**
     * @return array
     * @Template()
     */
    public function userNotFoundAction(){
        return array();
    }

    /**
     * @Template()
     * @param Request $request
     * @param $friendSlug
     * @return array
     */
    public function addFriendAction(Request $request, $friendSlug){
        /** @var FriendService $friendService */
        $friendService = $this->container->get('ensie.user_friend_service');
        if(!$friendEnsieUser = $this->getUserBySlug($friendSlug)){
            return $this->forward('EnsieUserBundle:User:userNotFound');
        }
        $ensieUser = $this->getUser();
        $session = $this->get('session');
        if($ensieUser instanceof EnsieUser and $ensieUser != $friendEnsieUser){
            if($friendService->addFriend($ensieUser, $friendEnsieUser)){
                $session->getFlashBag()->add(
                    'error',
                    'flashmessage.friend_added'
                );
                $this->getDoctrine()->getManager()->flush();
            } else {
                $session->getFlashBag()->add(
                    'error',
                    'flashmessage.friend_already_friend'
                );
            }
        } elseif($ensieUser == $friendEnsieUser){
            $session->getFlashBag()->add(
                'error',
                'flashmessage.friend_cant_friend_yourself'
            );
        }
        else{
            $session->getFlashBag()->add(
                'error',
                'flashmessage.friend_user_need_to_be_loggedin_to_add_friend'
            );
        }
        return $this->redirectReferer($request);
    }

    /**
     * @Template()
     * @param Request $request
     * @param $friendSlug
     * @return array
     */
    public function removeFriendAction(Request $request, $friendSlug){
        /** @var FriendService $friendService */
        $friendService = $this->container->get('ensie.user_friend_service');
        if(!$friendEnsieUser = $this->getUserBySlug($friendSlug)){
            return $this->forward('EnsieUserBundle:User:userNotFound');
        }
        $ensieUser = $this->getUser();
        $session = $this->get('session');
        if($ensieUser instanceof EnsieUser){
            if($friendService->removeFriend($ensieUser, $friendEnsieUser)){
                $session->getFlashBag()->add(
                    'error',
                    'flashmessage.friend_removed'
                );
                $this->getDoctrine()->getManager()->flush();
            } else {
                $session->getFlashBag()->add(
                    'error',
                    'flashmessage.friend_not_found'
                );
            }
        }
        else{
            $session->getFlashBag()->add(
                'error',
                'flashmessage.friend_user_need_to_be_loggedin_to_remove_friend'
            );
        }

        return $this->redirectReferer($request);
    }

    /**
     * @Template()
     * @param int $maxResult
     * @return array
     */
    public function newUserListAction($maxResult = 5){
        /** @var EnsieUserRepository $ensieUserRepository */
        $ensieUserRepository = $this->container->get('ensie.user_ensie_user_repository');
        $list = $ensieUserRepository->getNewList($maxResult, $this->getCurrentLanguage());
        return array('list' => $list);
    }

    /**
     * @Template()
     * @param int $maxResult
     * @return array
     */
    public function popularUserListAction($maxResult = 5){
        /** @var PopularUserRepository $popularUserRepository */
        $popularUserRepository = $this->container->get('ensie.user_popular_user_repository');
        $list = $popularUserRepository->getPopularUsersRepository($this->getCurrentLanguage(), $maxResult);
        return array('list' => $list);
    }

    /**
     * @Template()
     * @param int $maxResult
     * @return array
     */
    public function bestUserListAction($maxResult = 5){
        /** @var EnsieUserRepository $ensieUserRepository */
        $ensieUserRepository = $this->container->get('ensie.user_ensie_user_repository');
        $list = $ensieUserRepository->getBestList($maxResult, $this->getCurrentLanguage());
        return array('list' => $list);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function redirectReferer(Request $request){
        $referer = $request->headers->get('referer');
        if(!empty($referer) and strstr($referer, $_SERVER['HTTP_HOST'])){
            return $this->redirect($referer);
        }
        return $this->redirect($this->generateUrl('home'));
    }

    /**
     * @param $userSlug
     * @return mixed
     */
    private function getUserBySlug($userSlug){
        /** @var EnsieUserRepository $ensieUserRepository */
        $ensieUserRepository = $this->container->get('ensie.user_ensie_user_repository');
        $ensieUser = $ensieUserRepository->getUserBySlug($userSlug);
        return $ensieUser;
    }
}