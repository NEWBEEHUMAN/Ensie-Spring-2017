<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 13-8-14
 * Time: 12:24
 */

namespace Ensie\MailerBundle\MailParameters;

use Ensie\UserBundle\Entity\EnsieUser;

class SendFriendAddedMailParameters extends AbstractMailParameters {

    function __construct(EnsieUser $user, EnsieUser $ensieUserFriend)
    {
       $this->addParameter('ensieUser', $user);
       $this->addParameter('ensieUserFriend', $ensieUserFriend);
    }

    /**
     * @return string
     */
    function getTemplate()
    {
        return "EnsieMailerBundle:User:sendAddedToFavorite.html.twig";
    }

    /**
     * @return string
     */
    function getSubject()
    {
        return "mail.user.added_to_favo";
    }


} 