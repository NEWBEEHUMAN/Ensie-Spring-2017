<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 21-6-14
 * Time: 11:49
 */

namespace Ensie\EnsieBundle\Form;

use Ensie\EnsieBundle\Entity\EnsieRepository;
use Ensie\EnsieBundle\Form\Datatransformer\EnsieToTitleTransformer;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\LanguageBundle\Form\LanguageSelectorType;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class KeywordType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('word');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ensie\EnsieBundle\Entity\Keyword'
        ));
    }

    public function getName()
    {
        return 'keyword';
    }
} 