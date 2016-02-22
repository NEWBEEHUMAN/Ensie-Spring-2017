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
use Sonata\AdminBundle\Route\RouteCollection;


class SubscriptionLogAdmin extends Admin
{

    /**
     * Default Datagrid values
     *
     * @var array
     */
    protected $datagridValues = array(
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
    );

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('create');
        $collection->remove('delete');
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('subscription', null, array('label' => 'Subscription'))
            ->add('user', null, array('label' => 'User'))
            ->add('email', null, array('label' => 'Email'))
            ->add('companyName', null, array('label' => 'Company'))
            ->add('ipAddress', null, array('label' => 'Ip address'))
            ->add('createdAt', null, array('label' => 'Created'))
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('subscription', null, array('label' => 'Subscription'))
            ->add('user', null, array('label' => 'User'))
            ->add('email', null, array('label' => 'Email'))
            ->add('companyName', null, array('label' => 'Company'))
            ->add('ipAddress', null, array('label' => 'Ip address'))
            ->add('createdAt', null, array('label' => 'Created'))
            ->add('_action', 'actions', array(
                'actions' => array()
            ))
        ;
    }
}