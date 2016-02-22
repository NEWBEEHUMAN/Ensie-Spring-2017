<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 20-5-14
 * Time: 21:21
 */

namespace Ensie\EnsieBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class DefinitionAdmin extends Admin
{
    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'EnsieEnsieBundle:Definition:admin_base_edit.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('ensieUser', 'entity', array(
                'class' => 'Ensie\UserBundle\Entity\EnsieUser',
                'label' => 'User'
            ))
            ->add('ensie', 'entity', array(
                'class' => 'Ensie\EnsieBundle\Entity\Ensie',
                'label' => 'Ensie'
            ))
            ->add('language', 'entity', array(
                'class' => 'Ensie\LanguageBundle\Entity\Language',
                'label' => 'Language'
            ))
            ->add('word', 'text', array(
                'label' => 'Word'
            ))
            ->add('definition', 'textarea', array(
                'label' => 'Definition'
            ))
            ->add('description', 'textarea', array(
                'label' => 'Description'
            ))
            ->add('description', 'textarea', array(
                'label' => 'Description'
            ))
            ->add('extraLinkText', null, array(
                'label' => 'Link text'
            ))
            ->add('extraLinkUrl', null, array(
                'label' => 'Link url'
            ))
            ->add('validated', null, array(
                'label' => 'Validated'
            ))
            ->add('subcategory', 'sonata_type_model',array('required' => false), array('placeholder' => 'Geen category'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('ensieUser', null, array('class' => 'Ensie\UserBundle\Entity\EnsieUser'))
            ->add('word', null, array('label' => 'Word'))
            ->add('validated', null, array('label' => 'Validated'))
            ->add('language', null, array('label' => 'Language'))
            ->add('subcategory', null, array('label' => 'Subcategory'))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('word', null, array('label' => 'Word'))
            ->add('language', null, array('label' => 'Language'))
            ->add('definition', null, array('label' => 'Definition'))
            ->add('validated', null, array('label' => 'Validated', "editable" => true))
            ->add('created', null, array('label' => 'Created'))
            ->add('updated', null, array('label' => 'Updated'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                )
            ))
        ;
    }
}