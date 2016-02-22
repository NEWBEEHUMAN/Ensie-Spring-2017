<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 16-8-14
 * Time: 11:32
 */

namespace Ensie\UserBundle\Form;


use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class RegisterType extends RegistrationFormType {

    /** @var  SessionInterface */
    private $session;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder->remove('username'); // we use email as the username
        $builder->add('subscription', 'entity', array(
                'class' => 'EnsieSubscriptionBundle:Subscription',
                'expanded' => true,
                'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->where('s.isCompany = true')
                            ->orderBy('s.position', 'ASC');
                    })
        );
        $builder->add('companyName');
        $builder->add('firstName');
        $builder->add('lastName');
        $builder->add('termsAccepted', 'checkbox', array('required' => true));
        $builder->add('isCompany', 'hidden', array('data' => true));
        $builder->add('locale', 'hidden', array('data' => $this->session->get('_locale')));
    }

    public function setSession(SessionInterface $session){
        $this->session = $session;
    }

    public function getName()
    {
        return 'ensie_user_registration';
    }

} 