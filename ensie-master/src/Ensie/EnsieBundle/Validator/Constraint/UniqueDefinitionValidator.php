<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 11-9-14
 * Time: 19:51
 */

namespace Ensie\EnsieBundle\Validator\Constraint;


use Ensie\EnsieBundle\Entity\DefinitionRepository;
use Ensie\LanguageBundle\Service\LanguageService;
use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueDefinitionValidator extends ConstraintValidator{

    use GetCurrentLanguageTrait;

    /** @var  DefinitionRepository */
    private $definitionRepository;

    /** @var  SecurityContext */
    private $securityContext;

    /** @var  LanguageService */
    private $languageService;

    function __construct($definitionRepository, $languageService, $securityContext)
    {
        $this->definitionRepository = $definitionRepository;
        $this->languageService = $languageService;
        $this->securityContext = $securityContext;
    }

    public function validate($value, Constraint $constraint)
    {
        /** @var UniqueEnsie $constraint */
        /** @var EnsieUser $ensieUser */
        if($value != null)
        {
            $ensieUser = $this->securityContext->getToken()->getUser();
            $language = $this->languageService->getCurrentLanguage();
            $definition = $this->definitionRepository->getByWordUserLanguage($value, $ensieUser, $language);
            if($definition){
                $this->context->addViolation($constraint->message, array('%string%' => $value));
            }
        }
    }
} 