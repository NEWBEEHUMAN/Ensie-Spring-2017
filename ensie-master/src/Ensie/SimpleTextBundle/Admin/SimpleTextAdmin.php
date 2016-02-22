<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 20-5-14
 * Time: 15:23
 */

namespace Ensie\SimpleTextBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Ensie\SimpleTextBundle\Entity\SimpleText;

class SimpleTextAdmin extends Admin
{
    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'EnsieSimpleTextBundle:SonataAdmin:base_edit_tinymce.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }

    public function getNewInstance()
    {
        /** @var SimpleText $instance */
        $instance = parent::getNewInstance();
        $request = $this->getRequest();
        if($identifier = $request->get('identifier')){
            $instance->setIdentifier($identifier);
        }
        return $instance;
    }


    protected function configureFormFields(FormMapper $formMapper)
    {
        $editorClass = 'tinymce';
        if(!$this->getRequest()->get('useEditor')){
            $editorClass = '';
        }
        $formMapper
            ->add('identifier', 'textarea')
            ->add('translations', 'a2lix_translations', array(

                    'fields' => array(
                        'simpleText' => array(
                            'label' => 'Tekst', // Custom label
                            'field_type' => 'textarea',
                            'attr' => array('class' => $editorClass)
                        ),
                    )
                )
            );
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('identifier')
            ->add('updatedAt')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('identifier')
            ->add('updatedAt')
        ;
    }
}