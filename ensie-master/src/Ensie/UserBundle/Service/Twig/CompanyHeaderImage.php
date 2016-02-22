<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 16-6-14
 * Time: 12:15
 */

namespace Ensie\UserBundle\Service\Twig;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\UserBundle\Entity\EnsieUser;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Routing\RouterInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Twig_SimpleFunction;

class CompanyHeaderImage extends \Twig_Extension
{
    /** @var CacheManager */
    private $liiCacheManager;

    /** @var  UploaderHelper */
    private $uploaderHelper;

    function __construct(CacheManager $liiCacheManager, UploaderHelper $uploaderHelper)
    {
        $this->liiCacheManager = $liiCacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('getCompanyHeaderImage', array($this, 'getCompanyHeaderImage')),
        );
    }

    /**
     * @param EnsieUser $ensieUser
     * @param $size
     * @return string
     */
    public function getCompanyHeaderImage(EnsieUser $ensieUser, $size)
    {
        if($ensieUser->getPicture()){
            $path = $this->uploaderHelper->asset($ensieUser, 'profile_header');
        }
        if(!isset($path) or !file_exists($_SERVER['DOCUMENT_ROOT'] . $path)){
            $path = '/img/user.png';
        }
        $url = $this->liiCacheManager->getBrowserPath(str_replace('//', '/', $path), $size);
        return $url;
    }

    public function getName()
    {
        return 'ensie_user_company_header_image';
    }
}