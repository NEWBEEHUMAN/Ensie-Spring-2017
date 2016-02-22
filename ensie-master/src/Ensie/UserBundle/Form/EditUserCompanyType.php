<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 31-8-14
 * Time: 10:48
 */

namespace Ensie\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EditUserCompanyType extends EditUserType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder->add('companyName', 'text');
        $builder->add('websiteUrl', 'text');
        $builder->add('telnumber', 'text');
        $builder->add('headerImageFile', 'file', array('label' => 'Header image', 'required' => true));
    }


    public function getName()
    {
        return 'ensie_user_profile_company';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'validation_groups' => array('Default', 'Company'),
        ));
    }

    public function getTemplatePath()
    {
        return 'EnsieUserBundle:Profile:user_edit_company_form.html.twig';
    }


} 