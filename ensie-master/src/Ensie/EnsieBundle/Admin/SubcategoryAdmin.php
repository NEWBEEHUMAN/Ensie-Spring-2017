<?php

namespace Ensie\EnsieBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class SubcategoryAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('subtitle')
            ->add('headerImageFile', 'file', array('label' => 'Header image', 'required' => false))
            ->add('tileImageFile', 'file', array('label' => 'Tile image', 'required' => false))
            ->add('logoImageFile', 'file', array('label' => 'Logo image', 'required' => false))
            ->add('category', 'sonata_type_model', array('label' => 'Categorie'))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('title', null, array('label' => 'Titel'))
            ->add('subtitle', null, array('label' => 'Subtitel'))
            ->add('category', null, array('label' => 'Categorie'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                )
            ))
        ;
    }
}