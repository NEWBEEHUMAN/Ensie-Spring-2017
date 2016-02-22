<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 9-11-14
 * Time: 11:00
 */

namespace Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter;


use Ensie\UserBundle\Entity\EnsieUser;


class UserDataGetter extends  AbstractDataGetter{

    /** @var  EnsieUser */
    protected $ensieUser;

    public function setUser(EnsieUser $ensieUser){
        $this->ensieUser = $ensieUser;
    }

    public function gatherData()
    {
        if($this->ensieUser->getFormattedName()){
            $this->containsData = true;
        };
        return array('FORMATTEDNAME' => $this->ensieUser->getFormattedName());
    }

    public function validate()
    {
        if(!isset($this->ensieUser) or empty($this->ensieUser)){
            throw new \Exception('User is not set. Use the setter ');
        }
    }
} 