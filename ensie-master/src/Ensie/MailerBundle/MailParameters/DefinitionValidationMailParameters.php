<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 13-8-14
 * Time: 12:24
 */

namespace Ensie\MailerBundle\MailParameters;

use Ensie\EnsieBundle\Entity\Definition;

class DefinitionValidationMailParameters extends AbstractMailParameters {

    function __construct(Definition $definition)
    {
       $this->addParameter('definition', $definition);
    }

    /**
     * @return string
     */
    function getTemplate()
    {
        return "EnsieMailerBundle:AdminMail:definitionValidation.html.twig";
    }

    /**
     * @return string
     */
    function getSubject()
    {
        return "mail.admin.definition_validate";
    }


} 