<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 17-4-2015
 * Time: 12:52
 */

namespace Ensie\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class ContactType  extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email', array(
            'required' => true
        ));
        $builder->add('message', 'textarea', array(
            'required' => true
        ));
        $builder->add('url', 'hidden');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ensie\UserBundle\Model\Contact'
        ));
    }

    public function getName()
    {
        return 'ensie_user_contact';
    }

}