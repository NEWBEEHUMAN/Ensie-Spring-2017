<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 20-5-14
 * Time: 21:21
 */

namespace Ensie\UserBundle\Admin;

use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;

class PopularUserAdmin extends Admin
{

    public function createQuery($context = 'list')
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = $this->getModelManager()->getEntityManager($this->getClass())->createQueryBuilder();

        $queryBuilder->select('popularUser')
            ->from($this->getClass(), 'popularUser')
            ->addOrderBy('popularUser.language', 'DESC')
            ->addOrderBy('popularUser.position', 'ASC');
        $proxyQuery = new ProxyQuery($queryBuilder);
        return $proxyQuery;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('language', 'entity', array(
                'class' => 'Ensie\LanguageBundle\Entity\Language',
                'property' => 'locale',
                'label' => 'Taal'
            ))
            ->add('ensieUser', 'entity', array(
                'class' => 'Ensie\UserBundle\Entity\EnsieUser',
                'property' => 'formattedname',
                'label' => 'Gebruiker'
            ))
            ->add('position', 'number', array(
                'label' => 'Positie'
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('language', '', array(
                'associated_property' => 'locale',
                'label' => 'Taal'
            ))
            ->add('ensieUser', '', array(
                'associated_property' => 'formattedname',
                'label' => 'Gebruiker'
            ))
            ->add('position', '', array(
                'sortable'=>false,
                'label' => 'Positie'
            ))
            ->add('_action', 'actions',
                array(
                    'actions' => array(
                        'edit' => array(),
                        'delete' => array()
                    )
                ))
        ;
    }
}
