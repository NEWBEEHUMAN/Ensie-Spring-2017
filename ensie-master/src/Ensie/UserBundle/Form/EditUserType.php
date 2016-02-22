<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 31-8-14
 * Time: 10:48
 */

namespace Ensie\UserBundle\Form;

use Ensie\SimpleTextBundle\Service\Twig\SimpleTextExtension;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EditUserType extends AbstractType implements EditUserTypeInterface{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add your custom field
        $builder->add('formattedName', 'text');
        $builder->add('username', 'text');
        $builder->add('email', 'text');
        $builder->add('headline', 'textarea', array(
            'attr' => array(
                'class' => 'char-counter',
                'data-length' => '50',
            )
        ));
        $builder->add('bio', 'textarea', array(
            'required' => false,
            'attr' => array(
                'class' => 'char-counter',
                'data-length' => '140',
            )
        ));
        $builder->add('receiveEmails', 'checkbox', array(
            'required'  => false
        ));
        $builder->add('pictureFile', 'file', array(
            'required' => false
        ));
        $builder->add('headerImageFile', 'file', array('label' => 'Header image', 'required' => false));
        $builder->add('linkedInUrl', 'text', array(
            'required' => false
        ));
        $builder->add('googlePlusUrl', 'text', array(
            'required' => false
        ));

        $builder->add('contactSetting', 'choice', array(
            'required' => true,
            'choices' => EnsieUser::getContactSettingOptions(),
            'expanded' => true
        ));
        //$builder->add('language');
    }


    public function getName()
    {
        return 'ensie_user_profile';
    }

    public function getTemplatePath()
    {
       return  'EnsieUserBundle:Profile:user_edit_form.html.twig';
    }


} 