<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 13-8-14
 * Time: 12:24
 */

namespace Ensie\MailerBundle\MailParameters;

use Ensie\EnsieBundle\Entity\Definition;

class DefinitionRejectedMailParameters extends AbstractMailParameters {

    function __construct(Definition $definition, $feedback)
    {
        $this->addParameter('definition', $definition);
        $this->addParameter('feedback', $feedback);
    }

    /**
     * @return string
     */
    function getTemplate()
    {
        return "EnsieMailerBundle:User:sendDefinitionRejected.html.twig";
    }

    /**
     * @return string
     */
    function getSubject()
    {
        return "mail.user.definition_rejected";
    }


} 