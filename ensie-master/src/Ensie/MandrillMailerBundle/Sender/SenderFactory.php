<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 7-11-14
 * Time: 17:39
 */

namespace Ensie\MandrillMailerBundle\Sender;


class SenderFactory {

    private $config;

    function __construct($config)
    {
        $this->config = $config;
    }

    function build($templateId){
        $sender = new Sender('marijn@noxit.nl', 'Test Marijn');
        return $sender;
    }

} 