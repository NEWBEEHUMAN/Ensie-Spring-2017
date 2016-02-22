<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 13-8-14
 * Time: 12:11
 */

namespace Ensie\MailerBundle\MailParameters;


abstract class AbstractMailParameters {

    protected $parameters = array();

    protected function addParameter($key, $value){
        $this->parameters[$key] = $value;
    }

    public function getParameters(){
        return $this->parameters;
    }

    /**
     * @return string
     */
    abstract function getTemplate();

    /**
     * @return string
     */
    abstract function getSubject();
} 