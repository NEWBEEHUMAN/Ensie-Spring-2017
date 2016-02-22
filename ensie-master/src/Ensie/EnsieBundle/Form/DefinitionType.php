<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 21-6-14
 * Time: 11:49
 */

namespace Ensie\EnsieBundle\Form;

use Ensie\EnsieBundle\Entity\Definition;
use Ensie\EnsieBundle\Entity\Ensie;
use Ensie\EnsieBundle\Entity\EnsieRepository;
use Ensie\EnsieBundle\Event\EventListeners\DynamicFormListener;
use Ensie\EnsieBundle\Form\Datatransformer\EnsieToTitleTransformer;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\LanguageBundle\Form\LanguageSelectorType;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DefinitionType extends AbstractType{

    /** @var  EnsieRepository */
    private $ensieRepository;

    /**
     * @var \Ensie\UserBundle\Entity\EnsieUser
     */
    private $ensieUser;

    /** @var \Ensie\LanguageBundle\Entity\Language  */
    private $language;


    /**
     * @param EnsieRepository $ensieRepository
     * @param EnsieUser $ensieUser
     * @param Language $language
     */
    function __construct(EnsieRepository $ensieRepository, EnsieUser $ensieUser, Language $language)
    {
        $this->ensieRepository = $ensieRepository;
        $this->ensieUser = $ensieUser;
        $this->language = $language;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('ensieSelect', 'entity', array(
            'class' => 'Ensie\EnsieBundle\Entity\Ensie',
            'choices' => $this->ensieRepository->getAllEnsiesByUser($this->ensieUser, $this->language),
            'property' => 'title',
            'property_path' => 'ensie',
            'mapped' => false
        ));
        $builder->add(
            $builder->create('ensieText', 'text', array(
                    'property_path' => 'ensie',
                    'mapped' => true
                ))->addModelTransformer(new EnsieToTitleTransformer($this->ensieRepository, $this->ensieUser, $this->language))
        );
        $builder->add('word', 'text');
        $builder->add('definition', 'textarea');
        $builder->add('description', 'textarea');
        $builder->add('keywords', 'collection', array(
            'type' => new KeywordType(),
            'allow_add'    => true,
            'error_bubbling' => false,
            'cascade_validation' => true,
            'by_reference' => false
        ));
        $builder->add('extraLinkText', 'text');
        $builder->add('extraLinkUrl', 'text');
        $builder->add('word', 'text');
        $builder->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ensie\EnsieBundle\Entity\Definition',
            'cascade_validation' => true
        ));
    }

    public function getName()
    {
        return 'definition';
    }
} 