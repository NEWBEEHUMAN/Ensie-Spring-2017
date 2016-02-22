<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 16-6-14
 * Time: 12:15
 */

namespace Ensie\UserBundle\Service\Twig;

use Ensie\UserBundle\Entity\EnsieUser;
use Ensie\UserBundle\Entity\EnsieUserRepository;
use Ensie\UserBundle\Service\FriendService;
use Symfony\Component\Routing\RouterInterface;

class Friend extends \Twig_Extension
{

    /**
     * @var FriendService
     */
    private $friendService;


    function __construct(FriendService $friendService)
    {
        $this->friendService = $friendService;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('isFriend', array($this, 'isFriend')),
        );
    }

    /**
     * @param EnsieUser $ensieUser
     * @param EnsieUser $ensieUserFriend
     * @return bool
     */
    public function isFriend($ensieUser, $ensieUserFriend)
    {
        if($ensieUser instanceof EnsieUser and $ensieUserFriend instanceof EnsieUser){
            return $this->friendService->isFriend($ensieUser, $ensieUserFriend);
        }
        return false;
    }

    public function getName()
    {
        return 'ensie_user_friend_extension';
    }
}