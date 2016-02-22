<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 14-9-14
 * Time: 18:25
 */

namespace Ensie\AdminBundle\Form;


use Ensie\EnsieBundle\Entity\Definition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DefinitionValidationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('feedback', 'textarea');
        $builder->add('accept', 'submit', array(
            'label' => 'accept',
            'attr' => array('class' => 'accept')
        ));
        $builder->add('reject', 'submit', array(
            'label' => 'reject',
            'attr' => array('class' => 'reject')
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'definition_validation';
    }
} 