<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 16-5-14
 * Time: 16:00
 */

namespace Ensie\LanguageBundle\Form;

use Ensie\LanguageBundle\Entity\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LanguageSelectorType extends AbstractType {

    /** @var  Language */
    private $selectedLanguageId = 0;

    /**
     * @param Language $selectedLanguage
     */
    function __construct(Language $selectedLanguage)
    {
        $this->selectedLanguageId = $selectedLanguage->getId();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var Language $selectedLanguage */
        $builder->add('language', 'entity', array(
            'property' => 'name',
            'class' => 'EnsieLanguageBundle:Language',
            'data' => $this->selectedLanguageId
        ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\Ensie\LanguageBundle\Entity\Language'
        ));
    }

    public function getName()
    {
        return 'language';
    }
}