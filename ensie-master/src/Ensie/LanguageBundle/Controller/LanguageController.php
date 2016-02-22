<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 20-6-14
 * Time: 12:01
 */

namespace Ensie\LanguageBundle\Controller;

use Ensie\LanguageBundle\Entity\Language;
use Ensie\LanguageBundle\Entity\LanguageRepository;
use Ensie\LanguageBundle\Service\LanguageService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class LanguageController extends Controller
{

    /**
     * This action is used in de base template
     * @Template()
     */
    public function languageSelectorAction(Request $request)
    {
        /** @var LanguageService $languageService */
        $languageService = $this->get('ensie.ensie_language_service');
        /** @var LanguageRepository $languageRepository */
        $languageRepository = $this->getDoctrine()->getRepository('EnsieLanguageBundle:Language');
        $countryLanguages = $languageRepository->getCountyLanguageName();
        $currentLanguage = $languageService->getCurrentLanguage();
        return array('languageSelector' => $this->createLanguageSelector(array('languages' => $countryLanguages), $currentLanguage));
    }

    private function createLanguageSelector(array $languages, Language $selectedLanguage){
        return $this->createFormBuilder($languages)
            ->add('languages', 'entity', array(
                'property' => 'name',
                'class' => 'EnsieLanguageBundle:Language',
                'data' => $selectedLanguage->getId()
                ))
            ->getForm()->createView();
    }
}