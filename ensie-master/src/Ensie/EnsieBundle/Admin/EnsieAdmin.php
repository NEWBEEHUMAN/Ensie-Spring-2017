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

class EnsieAdmin extends Admin
{
    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('ensieUser', 'entity', array('class' => 'Ensie\UserBundle\Entity\EnsieUser'))
            ->add('title', 'text', array('label' => 'Title'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('ensieUser', null, array('class' => 'Ensie\UserBundle\Entity\EnsieUser'))
            ->add('title')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('ensieUser', null, array('class' => 'Ensie\UserBundle\Entity\EnsieUser'))
            ->add('title', null, array('label' => 'Title'))
            ->add('created', null, array('label' => 'Created'))
            ->add('updated', null, array('label' => 'Updated'))
        ;
    }
}