<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 13-8-14
 * Time: 12:24
 */

namespace Ensie\MailerBundle\MailParameters;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\UserBundle\Entity\EnsieUser;

class DefinitionCreatedAdminMailParameters extends AbstractMailParameters {

    function __construct(EnsieUser $ensieUser, Definition $definition)
    {
        $this->addParameter('ensieUser', $ensieUser);
        $this->addParameter('definition', $definition);
    }

    /**
     * @return string
     */
    function getTemplate()
    {
        return "EnsieMailerBundle:AdminMail:sendArticleCreated.html.twig";
    }

    /**
     * @return string
     */
    function getSubject()
    {
        return "mail.user.definition_created";
    }
} 