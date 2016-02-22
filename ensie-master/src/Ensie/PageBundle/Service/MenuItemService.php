<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 18-9-14
 * Time: 9:03
 */

namespace Ensie\PageBundle\Service;


use Ensie\LanguageBundle\Traits\GetCurrentLanguageTrait;
use Symfony\Component\DependencyInjection\ContainerAware;

class MenuItemService extends ContainerAware{

    use GetCurrentLanguageTrait;

    public function getMenuItems(){
        if($this->getCurrentLanguage()->getLocale() == 'nl'){
            $allowedPages = array(
                'over', 'team', 'testimonials', 'bedrijven', 'stijlgids', 'faq', 'policy', 'contact', 'disclaimer'
            );
        } else{
            $allowedPages = array(
                'about', 'team', 'testimonials', 'companies', 'guidelines', 'faq', 'policy', 'contact', 'disclaimer'
            );
        }
        return $allowedPages;
    }
} 