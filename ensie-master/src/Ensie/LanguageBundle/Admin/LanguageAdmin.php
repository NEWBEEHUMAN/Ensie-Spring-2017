<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 20-5-14
 * Time: 15:23
 */

namespace Ensie\LanguageBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class LanguageAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('locale')
            ->add('writeable')
            ->add('translations', 'a2lix_translations', array(
                    'by_reference' => false
                )
            );
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('locale')
            ->add('writeable')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('locale')
            ->add('writeable')
        ;
    }
}