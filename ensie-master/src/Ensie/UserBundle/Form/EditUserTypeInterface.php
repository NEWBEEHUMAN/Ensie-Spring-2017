<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 30-1-15
 * Time: 19:00
 */

namespace Ensie\UserBundle\Form;

use Symfony\Component\Form\FormTypeInterface;

interface EditUserTypeInterface extends FormTypeInterface{

    public function getTemplatePath();
} 