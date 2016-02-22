<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 16-6-14
 * Time: 12:15
 */

namespace Ensie\UserBundle\Service\Twig;

use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Routing\RouterInterface;

class UserUrl extends \Twig_Extension
{

    /** @var RouterInterface */
    private $router;

    function __construct(RouterInterface $router)
    {
        $this->router = $router;

    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('ensieUserPath', array($this, 'detailUserPath')),
        );
    }

    /**
     * @param EnsieUser $ensieUser
     * @return string
     */
    public function detailUserPath(EnsieUser $ensieUser)
    {
        return $this->router->generate('user_detail', array(
            'userSlug' => $ensieUser->getSlug()
        ));
    }

    public function getName()
    {
        return 'ensie_user_extension';
    }
}