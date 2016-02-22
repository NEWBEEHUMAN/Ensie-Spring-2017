<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 11-9-14
 * Time: 19:50
 */

namespace Ensie\EnsieBundle\Validator\Constraint;


use Symfony\Component\Validator\Constraint;

class WordCount extends Constraint{

    public $minMessage = 'Need at least %min% words';
    public $maxMessage = 'No more then %max words are allowed';
    public $min;
    public $max;

} 