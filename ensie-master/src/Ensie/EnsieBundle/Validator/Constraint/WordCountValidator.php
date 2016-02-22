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

class WordCountValidator extends ConstraintValidator{

    public function validate($value, Constraint $constraint)
    {
        /** @var WordCount $constraint */
        if(str_word_count($value) < $constraint->min)
        {
            $this->context->addViolation($constraint->minMessage, array('%min%' => $constraint->min));
        }
        if(str_word_count($value) > $constraint->max)
        {
            $this->context->addViolation($constraint->maxMessage, array('%max%' => $constraint->max));
        }
    }
} 