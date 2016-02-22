<?php
/**
 * Created by PhpStorm.
 * User: Badjak
 * Date: 25-8-14
 * Time: 20:44
 */

namespace Ensie\EnsieBundle\Service\ArrayFormatter;


use Ensie\EnsieBundle\Entity\Definition;
use Ensie\UserBundle\Entity\EnsieUser;
use Symfony\Component\Routing\RouterInterface;

class AutoCompleteBuilder {

    private $router;

    function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function getAutoCompleteUserResult(EnsieUser $ensieUser){
        return array(
            'label' => ($ensieUser->isCompany()) ? $ensieUser->getCompanyName() : $ensieUser->getFormattedname(),
            'category' => "writer",
            'url' => $this->router->generate('user_detail', array('userSlug' => $ensieUser->getSlug()), RouterInterface::ABSOLUTE_URL)
        );
    }

    public function getAutoCompleteDefinitionResult(Definition $definition){
        return array(
            'label' => $definition->getWord(),
            'category' => "definition",
            'url' => $this->router->generate('definition_search_word', array('slug' => $definition->getSlug()), RouterInterface::ABSOLUTE_URL)
        );
    }
} 