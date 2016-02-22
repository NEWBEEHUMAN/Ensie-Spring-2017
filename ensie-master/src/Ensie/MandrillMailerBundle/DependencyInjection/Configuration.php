<?php

namespace Ensie\MandrillMailerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ensie_mandrill_mailer');
        $rootNode
            ->children()
                ->scalarNode('delivery_address')->defaultFalse()->end()
                ->scalarNode('sales_address')->defaultFalse()->end()
                ->arrayNode('templates')
                    ->isRequired()
                    ->prototype('array')
                        ->isRequired()
                        ->prototype('array')
                            ->isRequired()
                            ->requiresAtLeastOneElement()
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('name')->isRequired()->end()
                                    ->scalarNode('class')->isRequired()->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
