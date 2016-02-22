<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 20-5-14
 * Time: 15:23
 */

namespace Ensie\SubscriptionBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Ensie\SimpleTextBundle\Entity\SimpleText;

class SubscriptionAdmin extends Admin
{

    public function getTemplate($name)
    {
        switch ($name) {
            case 'create':
            case 'edit':
                return 'EnsieSimpleTextBundle:SonataAdmin:base_edit_tinymce.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $editorClass = 'tinymce';
        $formMapper
            ->add('identifier', 'text')
            ->add('isCompany', null, array(
                'label' => 'Is company',
                'required' => false
            ))
            ->add('isMostSold', null, array(
                'label' => 'Is most sold',
                'required' => false
            ))
            ->add('urlShowAtDefinition', null, array(
                'label' => 'Website url shown at definition',
                'required' => false
            ))
            ->add('wordUniqueness', null, array(
                'label' => 'Word is unique',
                'required' => false
            ))
            ->add('wordAmount', 'text', array(
                'label' => 'Word amount(0=~)'
            ))
            ->add('translations', 'a2lix_translations', array(
                    'fields' => array(
                        'title' => array(
                            'label' => 'Title', // Custom label
                            'field_type' => 'text',
                        ),
                        'description' => array(
                            'label' => 'Omschrijving', // Custom label
                            'field_type' => 'textarea',
                            'attr' => array('class' => $editorClass)
                        ),
                        'price' => array(
                            'label' => 'Prijs', // Custom label
                            'field_type' => 'text',
                        ),
                    )

                )
            );
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('identifier')
            ->add('updatedAt')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null)
            ->add('identifier')
            ->add('isCompany', null, array('label' => 'Is company', "editable" => true))
            ->add('isMostSold', null, array('label' => 'Is most sold', "editable" => true))
            ->add('urlShowAtDefinition', null, array('label' => 'Website url shown at definition', "editable" => true))
            ->add('wordUniqueness', null, array('label' => 'Word is unique', "editable" => true))
            ->add('wordAmount', null, array('label' => 'Amount of definitions'))
            ->add('updatedAt')
        ;
    }
}