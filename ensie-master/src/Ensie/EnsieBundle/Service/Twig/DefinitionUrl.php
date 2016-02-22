<?php
/**
 * Created by PhpStorm.
 * User: Marijn
 * Date: 16-6-14
 * Time: 12:15
 */

namespace Ensie\EnsieBundle\Service\Twig;

use Ensie\EnsieBundle\Entity\Definition;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class DefinitionUrl extends \Twig_Extension
{

    /** @var RouterInterface */
    private $router;

    function __construct(RouterInterface $router)
    {
        $this->router = $router;

    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('definitionPath', array($this, 'definitionPath')),
        );
    }

    /**
     * @param Definition $definition
     * @return string
     */
    public function definitionPath(Definition $definition)
    {
        return $this->router->generate('definition_definition', array(
            'userSlug' => $definition->getEnsieUser()->getSlug(),
            'definitionSlug' => $definition->getSlug(),
        ), UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function getName()
    {
        return 'ensie_definition_extension';
    }
}