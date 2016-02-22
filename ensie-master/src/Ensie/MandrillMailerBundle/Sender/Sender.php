<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 7-11-14
 * Time: 17:40
 */

namespace Ensie\MandrillMailerBundle\Sender;


class Sender {

    protected $name;
    protected $email;

    function __construct($email, $name)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            throw new \Exception($email .' is not a correct email');
        }
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }



} 