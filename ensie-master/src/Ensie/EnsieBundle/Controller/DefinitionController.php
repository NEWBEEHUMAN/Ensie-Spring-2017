<?php

namespace Ensie\EnsieBundle\Controller;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\EnsieBundle\Entity\Ensie;
use Ensie\EnsieBundle\Entity\EnsieRepository;
use Ensie\EnsieBundle\Entity\PopularDefinitionRepository;
use Ensie\EnsieBundle\Entity\RatingRepository;
use Ensie\EnsieBundle\Entity\SpamRepository;
use Ensie\EnsieBundle\Event\Events\DefinitionEvents;
use Ensie\EnsieBundle\Event\Events\DefinitionRatedEvent;
use Ensie\EnsieBundle\Event\Events\ViewDefinitionEvent;
use Ensie\EnsieBundle\Event\Events\ViewEvents;
use Ensie\EnsieBundle\Form\DefinitionType;
use Ensie\EnsieBundle\Service\ArrayFormatter\AutoCompleteBuilder;
use Ensie\LanguageBundle\Service\LanguageService;
use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\EnsieUserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;

class DefinitionController extends Controller
{
    use GetCurrentLanguageTrait;

    /**
     * @Template()
     */
    public function homeAction(Request $request)
    {
        $searchWord = ($request->query->get('q') ? $request->query->get('q') : '');
        $token = ($request->query->has('token') ? $request->query->get('token') : '');
        if($this->get('security.context')->isGranted('ROLE_ENSIE_USER')){
            return new RedirectResponse($this->get('router')->generate('profile_dashboard', array('q' => $searchWord, 'token' => $token)));
        }
        return array('translationPlaceHolders' => array('word' => $searchWord), 'login' => $request->query->has('login'), 'token' => $token);
    }

    /**
     * @param Request $request
     * @param string $slug
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Template()
     */
    public function searchAction(Request $request, $slug = '')
    {
        $definitionList = '';
        if(!empty($slug)){
            /** @var DefinitionRepository $definitionRepository */
            $definitionRepository = $this->getDoctrine()->getRepository('EnsieEnsieBundle:Definition');
            $definitionList = $definitionRepository->getBySlug($slug, $this->getCurrentLanguage());
        }

        //Word is not found redirect and throw an flash message
        if(empty($slug) or empty($definitionList)){
            $searchWord = $request->get('q');
            //$this->setWordNotFoundFlash($searchWord);
            return $this->redirect($this->generateUrl('word_not_found', array('searchWord' => $searchWord)));
        }
        $searchWord = $definitionList[0]->getWord();
        return array('searchWord' => $searchWord, 'list' => $definitionList, 'translationPlaceHolders' => array('word' => $searchWord));
    }

    /**
     * @param Request $request
     * @param string $searchWord
     * @return array
     * @Template()
     */
    public function wordNotFoundAction(Request $request, $searchWord = '')
    {
        return array('searchWord' => $searchWord);
    }

    /**
     * @param $userSlug
     * @param $definitionSlug
     * @return array
     * @Template()
     */
    public function definitionAction($userSlug, $definitionSlug)
    {
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->getDoctrine()->getRepository('EnsieEnsieBundle:Definition');
        /** @var Definition $definition */
        $definition = $definitionRepository->getByUserSlugAndDefinitionSlug($userSlug, $definitionSlug, $this->getCurrentLanguage());
        if(empty($definition)){
            return $this->forward('EnsieEnsieBundle:Definition:definitionNotFound');
        }
        //View event(put in service or repo?)
        /** @var EnsieUser $user */
        $user = $this->getUser();
        $event = new ViewDefinitionEvent($definition, $user);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch(ViewEvents::ENSIE_DEFINITION_VIEW, $event);

        //List of definitions in the Ensie
        $ensieDefinitionList = $definitionRepository->getByEnsie($definition->getEnsie(), $this->getCurrentLanguage(), array($definition->getId()), 5);

        return array('definition' => $definition, 'ensieDefinitionList' => $ensieDefinitionList);
    }

    /**
     * @return array
     * @Template()
     */
    public function definitionNotFoundAction(){
        return array();
    }

    /**
     * @param Request $request
     * @param $userSlug
     * @param $definitionSlug
     * @param $ratingValue
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addRatingAction(Request $request, $userSlug, $definitionSlug, $ratingValue){
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->getDoctrine()->getRepository('EnsieEnsieBundle:Definition');
        /** @var Definition $definition */
        $definition = $definitionRepository->getByUserSlugAndDefinitionSlug($userSlug, $definitionSlug, $this->getCurrentLanguage());
        if(empty($definition)){
            return $this->forward('EnsieEnsieBundle:Definition:definitionNotFound');
        }
        if(!($this->getUser() instanceof EnsieUser)){
            /** @var FlashBag $flashBag */
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('notice', 'definition.rating_need_to_be_logged_in');
        }
        elseif($this->getUser() == $definition->getEnsieUser()){
                /** @var FlashBag $flashBag */
                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->add('notice', 'definition.rating_can_not_rate_your_own_definition');
        } else {
            if($ratingValue < 6 and $ratingValue > 0){
                $feedback = $request->get('feedback');
                if(($ratingValue == 1 or $ratingValue == 2) and empty($feedback)){
                    /** @var FlashBag $flashBag */
                    $flashBag = $this->get('session')->getFlashBag();
                    $flashBag->add('error', 'definition.rating_add_feedback');
                } else {
                    /** @var FlashBag $flashBag */
                    $flashBag = $this->get('session')->getFlashBag();
                    $flashBag->add('error', 'definition.rating_added');
                    $em = $this->getDoctrine()->getManager();
                    /** @var RatingRepository $ratingRepository */
                    $ratingRepository = $this->get('ensie.ensie_rating_repository');
                    $rating = $ratingRepository->addRating($definition, $this->getUser(), $ratingValue, $feedback);
                    $rating->setDefinition($definition);
                    $em->flush();
                    $definition->setRatingCount($ratingRepository->countRatings($definition));
                    $em->flush();
                }
            }
        }

        return $this->forward('EnsieEnsieBundle:Definition:definition', array(
            'userSlug'  => $userSlug,
            'definitionSlug' => $definitionSlug,
        ));
    }

    /**
 * @param Request $request
 * @param $userSlug
 * @param $definitionSlug
 * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
 */
    public function reportSpamAction(Request $request, $userSlug, $definitionSlug){
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->getDoctrine()->getRepository('EnsieEnsieBundle:Definition');
        /** @var Definition $definition */
        $definition = $definitionRepository->getByUserSlugAndDefinitionSlug($userSlug, $definitionSlug, $this->getCurrentLanguage());
        if(empty($definition)){
            return $this->forward('EnsieEnsieBundle:Definition:definitionNotFound');
        }
        if(!($this->getUser() instanceof EnsieUser)){
            /** @var FlashBag $flashBag */
            $flashBag = $this->get('session')->getFlashBag();
            $flashBag->add('notice', 'definition.spam_reporting_need_to_be_logged_in');
        } else {
            $spamReport = $request->get('description');
            if($spamReport){
                /** @var SpamRepository $spamRepository */
                $spamRepository = $this->get('ensie.ensie_spam_repository');
                $spamRepository->create($definition, $this->getUser(), $spamReport);
                $em = $this->getDoctrine()->getManager();
                $em->flush();
                /** @var FlashBag $flashBag */
                $flashBag = $this->get('session')->getFlashBag();
                $flashBag->add('notice', 'definition.spam_reported');
            }
        }

        return $this->forward('EnsieEnsieBundle:Definition:definition', array(
            'userSlug'  => $userSlug,
            'definitionSlug' => $definitionSlug,
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function autoCompleteSearchAction(Request $request){
        $searchWord = $request->get('term');
        if($searchWord){
            /** @var DefinitionRepository $definitionRepository */
            $definitionRepository = $this->get('ensie.ensie_definition_repository');
            $definitionResult = $definitionRepository->autoCompleteSearch($searchWord, $this->getCurrentLanguage());

            /** @var EnsieUserRepository $ensieUserRepository */
            $ensieUserRepository = $this->get('ensie.user_ensie_user_repository');
            $userResult = $ensieUserRepository->autoCompleteSearch($searchWord, $this->getCurrentLanguage());

            /** @var AutoCompleteBuilder $autoCompleteBuilder */
            $autoCompleteBuilder = $this->get('ensie.auto_complete_builder');
            $formattedResult = array();
            foreach($definitionResult as $definition){
                $formattedResult[] = $autoCompleteBuilder->getAutoCompleteDefinitionResult($definition);
            }
            foreach($userResult as $user){
                $formattedResult[] = $autoCompleteBuilder->getAutoCompleteUserResult($user);
            }

            return new JsonResponse($formattedResult);
        }
        else{
            return new JsonResponse(array('message' => 'No result'));
        }
    }

    /**
     * @Template()
     */
    public function definitionListAction($maxResult = 5){
        /** @var DefinitionRepository $definitionRepository */
        $definitionRepository = $this->get('ensie.ensie_definition_repository');
        $definitionList = $definitionRepository->getList($maxResult, $this->getCurrentLanguage());
        return array('definitionList' => $definitionList);
    }

    /**
     * @Template()
     */
    public function popularDefinitionListAction($maxResult = 5){
        /** @var PopularDefinitionRepository $popularDefinitionRepository */
        $popularDefinitionRepository = $this->get('ensie.ensie_popular_definition_repository');
        $definitionList = $popularDefinitionRepository->getPopularDefinitions($this->getCurrentLanguage(), $maxResult);
        return array('definitionList' => $definitionList);
    }

    public function getEnsiesByUserAction($id)
    {
        $html = ""; // HTML as response
        /** @var EnsieUser $ensieUser */
        $ensieUser = $this->getDoctrine()
            ->getRepository('EnsieUserBundle:EnsieUser')
            ->find($id);

        $ensies = $ensieUser->getEnsies();

        foreach($ensies as $ensie){
            /** @var Ensie $ensie */
            $html .= '<option value="'.$ensie->getId().'" >'.$ensie->__toString().'</option>';
        }

        return new Response($html, 200);
    }

    /**
     * @param string $searchWord
     */
    private function setWordNotFoundFlash($searchWord = ''){
        /** @var Session $session */
        $session = $this->get('session');
        if(empty($searchWord)){
            $session->getFlashBag()->add(
                'error',
                'flashmessage.search_empty_word'
            );
        }else{
            $session->getFlashBag()->add(
                'error',
                'flashmessage.search_empty_word_%word%'
            );
        }
    }

}
