<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 9-11-14
 * Time: 10:59
 */

namespace Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter;


interface DataGetterInterface {

    public function addExtraData(array $data);
    public function gatherData();
    public function getData();
    public function preRequestData();
    public function postRequestData();
    public function validate();
    public function containsData();
} 