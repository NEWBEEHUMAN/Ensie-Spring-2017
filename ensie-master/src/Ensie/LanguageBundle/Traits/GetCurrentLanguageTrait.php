<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 26-7-14
 * Time: 16:44
 */

namespace Ensie\LanguageBundle\Traits;

use Ensie\LanguageBundle\Exceptions\NoContainerException;
use Ensie\LanguageBundle\Service\LanguageService;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait GetCurrentLanguageTrait {

    /**
     * @return \Ensie\LanguageBundle\Entity\Language|null
     * @throws \Ensie\LanguageBundle\Exceptions\NoContainerException
     */
    function getCurrentLanguage(){
        if(isset($this->container) and $this->container instanceof ContainerInterface){
            /** @var LanguageService $languageService */
            $languageService = $this->container->get('ensie.ensie_language_service');
            return $languageService->getCurrentLanguage();
        }
        else{
            throw new NoContainerException('No container found');
        }
    }

} 