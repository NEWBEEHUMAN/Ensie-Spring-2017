<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 14-9-14
 * Time: 14:18
 */

namespace Ensie\UserBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class EmptyOrRegex extends Constraint{

    public $message = 'The input is not valid';
    public $pattern = '';
} 