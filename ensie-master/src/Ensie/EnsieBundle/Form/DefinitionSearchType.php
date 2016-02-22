<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 21-6-14
 * Time: 11:49
 */

namespace Ensie\EnsieBundle\Form;

use Ensie\EnsieBundle\Entity\EnsieRepository;
use Ensie\EnsieBundle\Form\Datatransformer\EnsieToTitleTransformer;
use Ensie\LanguageBundle\Entity\Language;
use Ensie\LanguageBundle\Form\LanguageSelectorType;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DefinitionType extends AbstractType{

    /** @var  EnsieRepository */
    private $ensieRepository;

    /**
     * @var \Ensie\UserBundle\Entity\EnsieUser
     */
    private $ensieUser;

    /** @var \Ensie\LanguageBundle\Entity\Language  */
    private $selectedLanguage;


    /**
     * @param EnsieRepository $ensieRepository
     * @param EnsieUser $ensieUser
     * @param Language $selectedLanguage
     */
    function __construct(EnsieRepository $ensieRepository, EnsieUser $ensieUser, Language $selectedLanguage)
    {
        $this->ensieRepository = $ensieRepository;
        $this->ensieUser = $ensieUser;
        $this->selectedLanguage = $selectedLanguage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            $builder->create('definition', 'genemu_jqueryautocomplete_text', array(
                'class' => 'Ensie\EnsieBundle\Entity\Definition',
                //'choices' => $this->ensieRepository->getEnsiesByUser($this->ensieUser),
                'property' => 'title',
                //'allow_add'    => true,

            ))->addModelTransformer(new EnsieToTitleTransformer($this->ensieRepository, $this->ensieUser)));
        /*$builder->add('ensie', 'collection', array(
            'type' => new EnsieType(),
            'allow_add'    => true,
            //'class' => 'Ensie\EnsieBundle\Entity\Ensie',
            //'choices' => $this->ensieRepository->getEnsiesByUser($this->ensieUser),
            //'property' => 'title'
        ));*/
        $builder->add('word', 'text');
        $builder->add('definition', 'textarea');
        $builder->add('description', 'textarea');
        $builder->add('language', 'entity', array(
            'property' => 'name',
            'class' => 'EnsieLanguageBundle:Language',
            'data' => $this->selectedLanguage->getId()
        ));
        $builder->add('save', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ensie\EnsieBundle\Entity\Definition'
        ));
    }

    public function getName()
    {
        return 'definition';
    }
} 