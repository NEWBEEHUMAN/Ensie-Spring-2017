<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 9-11-14
 * Time: 14:00
 */

namespace Ensie\MandrillMailerBundle\Mandrill\Template;


class Template {

    protected $name;
    protected $dataGetterContainerKey;

    function __construct($name, $dataGetterContainerKey)
    {
        $this->dataGetterContainerKey = $dataGetterContainerKey;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDataGetterContainerKey()
    {
        return $this->dataGetterContainerKey;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

} 