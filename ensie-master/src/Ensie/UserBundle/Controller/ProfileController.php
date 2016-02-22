<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 26-8-14
 * Time: 20:38
 */

namespace Ensie\UserBundle\Controller;


use Doctrine\Common\Collections\ArrayCollection;
use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Entity\Ensie;
use Ensie\EnsieBundle\Entity\Keyword;
use Ensie\EnsieBundle\Entity\KeywordRepository;
use Ensie\EnsieBundle\Form\DefinitionType;
use Ensie\EnsieBundle\Form\EnsieType;
use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Ensie\NotificationBundle\Entity\NotificationRepository;
use Ensie\NotificationBundle\Notification\NotificationService;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Form\EditUserForm;
use Ensie\UserBundle\Form\EditUserLinkedInForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\RouterInterface;

class ProfileController extends Controller {

    use GetCurrentLanguageTrait;

    /**
     * @Template()
     */
    public function dashboardAction(Request $request){
        $searchWord = ($request->get('q') ? $request->get('q') : '');

        $ensieUser = $this->getUser();

        //Redirect de user to the profile page
        if($ensieUser->getFirstLogin()){
            return new RedirectResponse($this->container->get('router')->generate('profile_profile_edit'));
        }

        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->getDoctrine()->getManager()->getRepository('Ensie\EnsieBundle\Entity\Definition');
        $definitions = $definitionRepository->getByUser($ensieUser, $this->getCurrentLanguage(), 'viewCount', 'desc', 5);

        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = $this->getDoctrine()->getManager()->getRepository('Ensie\NotificationBundle\Entity\Notification');
        $notifications = $notificationRepository->getNotifications($ensieUser, 5);

        $status = $status = $request->get('status');

        return array(
            'definitions' => $definitions,
            'notifications' => $notifications,
            'keywords' => $this->getRandomKeywords(5),
            'ensieUser' => $this->getUser(),
            'status' => $status,
            'translationPlaceHolders' => array('word' => $searchWord)
        );
    }


    /**
     * @Template()
     */
    public function definitionWriteAction(Request $request){
        $word = $request->get('word');
        $form = $this->createDefinitionForm($word);
        $status = $this->handleDefinitionForm($request, $form);
        if(is_string($status)){
            /** @var Definition $definition */
            $definition = $form->getData();
            if(($status = 'create' or $status = 'edit') and $definition->getEnsieUser()->getEnabledWriter() and !$definition->getEnsieUser()->isCompany()){
                return new RedirectResponse($this->get('router')->generate('definition_definition',
                    array(
                        'userSlug' => $definition->getEnsieUser()->getSlug()
                    , 'definitionSlug' => $definition->getSlug()
                    , 'share' => '1'
                    , 'status' => $status

                    )));
            }
            elseif($status = 'create' and $definition->getEnsieUser()->isCompany()){
                return new RedirectResponse($this->get('router')->generate('profile_dashboard',
                    array('status' => 'company-' . $status)));
            }
            elseif(!$definition->getEnsieUser()->getEnabledWriter()){
                return new RedirectResponse($this->get('router')->generate('profile_dashboard',
                    array('status' => $status)));
            }
        }

        return array('form' => $form->createView(), 'keywords' => $this->getRandomKeywords(3), 'ensieUser' => $this->getUser());
    }

    /**
     * @Template()
     */
    public function definitionEditAction(Request $request, $slug){
        /** @var EnsieUser $ensieUser */
        $ensieUser = $this->getUser();
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->getDoctrine()->getManager()->getRepository('Ensie\EnsieBundle\Entity\Definition');
        $definition = $definitionRepository->getByUserSlugAndDefinitionSlug($ensieUser->getSlug(), $slug, $this->getCurrentLanguage());
        if(empty($definition)){
            /** @var FlashBag $flashBag */
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('error', 'profile.definition_edit_definition_not_found');
            return new RedirectResponse($this->container->get('router')->generate('profile_dashboard'));
        }
        $form = $this->createDefinitionForm('', $definition);
        $status = $this->handleDefinitionForm($request, $form);
        if(is_string($status)){
            /** @var Definition $definition */
            $definition = $form->getData();
            if(($status = 'create' or $status = 'edit') and $definition->getEnsieUser()->getEnabledWriter()){
                return new RedirectResponse($this->get('router')->generate('definition_definition',
                    array(
                        'userSlug' => $definition->getEnsieUser()->getSlug()
                    , 'definitionSlug' => $definition->getSlug()
                    , 'share' => '1'
                    , 'status' => $status

                    )));
            }
            elseif(!$definition->getEnsieUser()->getEnabledWriter()){
                return new RedirectResponse($this->get('router')->generate('profile_dashboard',
                    array('status' => $status)));
            }
        }
        return array('form' => $form->createView(), 'ensieUser' => $this->getUser());
    }

    /**
     * @Template()
     */
    public function definitionDeleteAction($slug){
        /** @var EnsieUser $ensieUser */
        $ensieUser = $this->getUser();
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->getDoctrine()->getManager()->getRepository('Ensie\EnsieBundle\Entity\Definition');
        $definition = $definitionRepository->getByUserSlugAndDefinitionSlug($ensieUser->getSlug(), $slug, $this->getCurrentLanguage());
        if(empty($definition)){
            /** @var FlashBag $flashBag */
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('error', 'profile.definition_delete_definition_not_found');
            return new RedirectResponse($this->container->get('router')->generate('profile_dashboard'));
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($definition);
        $em->flush();
        /** @var FlashBag $flashBag */
        $flashBag = $this->get('session')->getFlashBag();
        $flashBag->add('notice', 'profile.definition_delete_succes');
        return new RedirectResponse($this->container->get('router')->generate('profile_definitions'));
    }

    /**
     * @Template()
     */
    public function ensieEditAction(){
        return array('message' => 'edit definition' . $id);
    }

    /**
     * @Template()
     */
    public function ensieDeleteAction(){
        return array('message' => 'edit definition' . $id);
    }

    /**
     * @Template()
     */
    public function definitionsAction(Request $request){
        $ensieUser = $this->getUser();
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->getDoctrine()->getManager()->getRepository('Ensie\EnsieBundle\Entity\Definition');

        $order = 'desc';
        if($request->query->has('order') and $request->query->get('order') == 'asc'){
            $order = 'asc';
        }
        $definitions = $definitionRepository->getByUser($ensieUser, $this->getCurrentLanguage(), 'viewCount', $order);
        return array('order' => $order, 'definitions' => $definitions, 'ensieUser' => $this->getUser());
    }

    /**
     * @Template()
     */
    public function notificationsViewAction(){
        $ensieUser = $this->getUser();
        /** @var NotificationRepository $notificationRepository */
        $notificationRepository = $this->getDoctrine()->getManager()->getRepository('Ensie\NotificationBundle\Entity\Notification');
        /** @var ArrayCollection $notifications */
        $notifications = $notificationRepository->getNotifications($ensieUser);
        return array('notifications' => $notifications, 'ensieUser' => $this->getUser());
    }

    /**
     * @Template()
     */
    public function setNotificationToViewedAction(){
        $ensieUser = $this->getUser();
        /** @var NotificationService $notificationService */
        $notificationService = $this->get('ensie.notification_service');
        $notifications = $notificationService->getUnviewedNotifications($ensieUser);
        $notificationService->viewNotifications($notifications);
        $this->getDoctrine()->getManager()->flush();
        return array();
    }

    /**
     * @Template()
     */
    public function profileAction(Request $request){
        /** @var EnsieUser $ensieUser */
        $ensieUser = $this->getUser();

        /** @var \Ensie\UserBundle\Form\Factory\UserFormFactory $userFormFactory */
        $userFormFactory = $this->get('ensie_user.form_factory');
        $form = $userFormFactory->createUserEditForm($ensieUser);

        if($request->isMethod('post')){
            $form->handleRequest($request);
            if($form->isValid()){
                if($ensieUser->getFirstLogin()){
                    //We have shown the tutorial
                    $ensieUser->setFirstLogin(0);
                    $this->getDoctrine()->getManager()->flush();
                    return $this->forward('EnsieUserBundle:Profile:dashboard');
                }
                $this->getDoctrine()->getManager()->flush();
                /** @var FlashBag $flashBag */
                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->add('notice', 'profile.user_edit_succes');
            }
        }
        return array('form' => $form->createView(), 'ensieUser' => $ensieUser, 'userEditFormTemplatePath' => $userFormFactory->getUserEditTemplatePath($ensieUser));
    }

    private function getRandomKeywords($limit){
        /** @var KeywordRepository $keywordRepository */
        $keywordRepository = $this->get('ensie.ensie_keyword_repository');
        return $keywordRepository->getRandomDefinitionableKeywords($this->getUser(), $this->getCurrentLanguage(), $limit);
    }

    //New class
    public function createDefinitionForm($word, Definition $definition = null){
        if(!$definition){
            $definition = new Definition();
            $definition->setWord($word);
            $language = $this->getCurrentLanguage();
            $definition->setLanguage($language);
            $definition->setEnsieUser($this->getUser());
        }
        $form = $this->createForm(
            new DefinitionType(
                $this->getDoctrine()->getManager()->getRepository('Ensie\EnsieBundle\Entity\Ensie'),
                $this->getUser(),
                $this->getCurrentLanguage()
            ), $definition
        );
        return $form;
    }

    public function handleDefinitionForm(Request $request, Form $form){
        if($request->isMethod('post')){
            $form->handleRequest($request);
            //$keywords = $definition->getKeywords();
            if ($form->isValid()) {
                /** @var Definition $definition */
                $definition = $form->getData();
                if($definition->getId() > 0){ //Edit the definition
                    $em = $this->getDoctrine()->getManager();
                    $em->flush();
                    /** @var FlashBag $flashBag */
                    $flashBag = $this->get('session')->getFlashBag();
                    $flashBag->add('notice', 'profile.definition_edit_succes');
                    return 'edit';
                }
                else { //Create the definition
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($definition);
                    $em->flush();
                    /** @var FlashBag $flashBag */
                    $flashBag = $this->get('session')->getFlashBag();
                    $flashBag->add('notice', 'profile.definition_create_succes');
                    return 'create';
                }
            }
        }
        return null;
    }
}