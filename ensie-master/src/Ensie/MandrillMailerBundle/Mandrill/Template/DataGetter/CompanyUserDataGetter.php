<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 9-11-14
 * Time: 11:00
 */

namespace Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter;

class CompanyUserDataGetter extends UserDataGetter{

    public function gatherData()
    {
        if($this->ensieUser->getFormattedName()){
            $this->containsData = true;
        };
        return array(
            'FORMATTEDNAME' => $this->ensieUser->getFormattedName(),
            'COMPANYNAME' => $this->ensieUser->getCompanyName()
        );
    }
} 