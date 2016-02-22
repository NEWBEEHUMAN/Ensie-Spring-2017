<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 7-11-14
 * Time: 21:00
 */

namespace Ensie\MandrillMailerBundle\Mandrill\Template;


class TemplateConfiguration {

    protected $templates;

    function __construct(array $templates)
    {
        $this->templates = $templates;
    }

    /**
     * @param $category
     * @param $locale
     * @param $type
     * @return Template
     * @throws \RuntimeException
     */
    public function get($category, $locale, $type)
    {
        if(isset($this->templates[$category]) and
            isset($this->templates[$category][$locale]) and
            isset($this->templates[$category][$locale][$type])
        ){
            $templateData = $this->templates[$category][$locale][$type];
            return new Template($templateData['name'], $templateData['class']);
        }
        else {
            $templateName =  $category . '-' . $locale . '-' . $type;
            throw new \RuntimeException(sprintf('Could not find template slug for template (WATCH THE - and _): %s', $templateName));
        }
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->templates;
    }


} 