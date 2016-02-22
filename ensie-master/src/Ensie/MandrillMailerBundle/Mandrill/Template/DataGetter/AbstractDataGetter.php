<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 9-11-14
 * Time: 11:04
 */

namespace Ensie\MandrillMailerBundle\Mandrill\Template\DataGetter;


abstract class AbstractDataGetter implements DataGetterInterface{

    protected $data;
    protected $extraData;
    protected $containsData = false;

    abstract public function gatherData();

    abstract public function validate();

    /**
     * @param array $data
     */
    public function addExtraData(array $data)
    {
        $this->extraData = $data;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $this->preRequestData();
        $this->data = array_merge($this->extraData, $this->gatherData());
        $this->postRequestData();
        return $this->data;
    }

    /**
     *
     */
    public function preRequestData()
    {
        $this->containsData = false;
        $this->validate();
    }

    /**
     *
     */
    public function postRequestData()
    {
    }

    /**
     * @return bool
     */
    public function containsData()
    {
        return $this->containsData;
    }
}