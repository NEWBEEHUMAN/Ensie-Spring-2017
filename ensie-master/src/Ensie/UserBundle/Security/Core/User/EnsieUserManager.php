<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 11-5-14
 * Time: 12:58
 */

namespace Ensie\UserBundle\Security\Core\User;

use Ensie\LanguageBundle\Service\LanguageService;
use FOS\UserBundle\Doctrine\UserManager;
use FOS\UserBundle\Model\UserManagerInterface;

class EnsieUserManager extends UserManager implements UserManagerInterface {

    /** @var  LanguageService */
    private $languageService;

    /**
     * @param \Ensie\LanguageBundle\Service\LanguageService $languageService
     */
    public function setLanguageService($languageService)
    {
        $this->languageService = $languageService;
    }

    /**
     * @return \Ensie\LanguageBundle\Entity\Language|null
     */
    public function getCurrentLanguage()
    {
        return $this->languageService->getCurrentLanguage();
    }

}