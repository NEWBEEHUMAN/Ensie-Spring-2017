<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 9-11-14
 * Time: 11:00
 */

namespace Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter;

class DefinitionRejectedDataGetter extends UserDataGetter{

    public function validate()
    {
        parent::validate(); // TODO: Change the autogenerated stub
        if(empty($this->extraData) or !isset($this->extraData['PROFILEDEFINITIONWRITE'])){
            throw new \Exception('PROFILEDEFINITIONWRITE is not set.');
        }
        if(empty($this->extraData) or !isset($this->extraData['FEEDBACK'])){
            throw new \Exception('FEEDBACK is not set.');
        }
    }

} 