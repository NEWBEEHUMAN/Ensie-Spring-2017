<?php
namespace Ensie\WallOfFameBundle\Service\Twig;
use Ensie\WallOfFameBundle\Service\WallOfFameService;


/**
 * Created by PhpStorm.
 * User: vladyslav
 * Date: 02.05.15
 * Time: 0:45
 */

class WallOfFameTwigExtension  extends \Twig_Extension{

    /**
     * @var WallOfFameService
     */
    private $wallOfFameService;

    /**
     * @param $wallOfFameService
     */
    public function __construct($wallOfFameService)
    {
        $this->wallOfFameService = $wallOfFameService;
    }


    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('isUserInTop100', array($this, 'isUserInTop100'))
        );
    }

    public function isUserInTop100($currentUser,$locale){
        $users = $this->wallOfFameService->get100UsersForWallOfFameByLocale($locale);
        return in_array($currentUser,$users);
    }
    
    public function getName()
    {
        return 'wall_of_fame_extension';
    }

}