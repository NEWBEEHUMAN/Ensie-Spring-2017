<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 11-9-14
 * Time: 19:51
 */

namespace Ensie\EnsieBundle\Validator\Constraint;


use Ensie\EnsieBundle\Entity\EnsieRepository;
use Ensie\LanguageBundle\Service\LanguageService;
use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEnsieValidator extends ConstraintValidator{

    use GetCurrentLanguageTrait;

    /** @var  EnsieRepository */
    private $ensieRepository;

    /** @var  SecurityContext */
    private $securityContext;

    /** @var  LanguageService */
    private $languageService;

    function __construct($ensieRepository, $languageService, $securityContext)
    {
        $this->ensieRepository = $ensieRepository;
        $this->languageService = $languageService;
        $this->securityContext = $securityContext;
    }

    public function validate($value, Constraint $constraint)
    {
        /** @var OneEnsiePerUserLanguageConstraint $constraint */
        /** @var EnsieUser $ensieUser */
        if($value){
            $ensieUser = $this->securityContext->getToken()->getUser();
            $language = $this->languageService->getCurrentLanguage();
            $ensie = $this->ensieRepository->getByTitleUserLanguage($value, $ensieUser, $language);
            if($ensie){
                $this->context->addViolation($constraint->message, array('%string%' => $value));
            }
        }
    }
} 