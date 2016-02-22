<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 13-8-14
 * Time: 12:24
 */

namespace Ensie\MailerBundle\MailParameters;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\Spam;
use Ensie\UserBundle\Entity\EnsieUser;

class ReportSpamToAdminMailParameters extends AbstractMailParameters {

    function __construct(Definition $definition, EnsieUser $ensieUser, Spam $spam)
    {
        $this->addParameter('ensieUser', $ensieUser);
        $this->addParameter('spam', $spam);
        $this->addParameter('definition', $definition);
    }

    /**
     * @return string
     */
    function getTemplate()
    {
        return "EnsieMailerBundle:AdminMail:definitionMarkedAsSpam.html.twig";
    }

    /**
     * @return string
     */
    function getSubject()
    {
        return "mail.admin.definition_marked_as_spam";
    }


} 