<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 16-5-14
 * Time: 16:00
 */

namespace Ensie\EnsieBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EnsieType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text');
        $builder->add('Save', 'submit');
        //$builder->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ensie\EnsieBundle\Entity\Ensie'
        ));
    }

    public function getName()
    {
        return 'ensie';
    }
}