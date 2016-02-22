<?php

namespace Ensie\CronBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('ensie_cron');

        $rootNode
            ->children()
                ->arrayNode('mailer')
                ->isRequired()
                    ->children()
                        ->arrayNode('reminder')
                        ->isRequired()
                            ->children()
                                ->integerNode('first')->isRequired()->min(1)->end()
                                ->integerNode('second')->isRequired()->min(2)->end()
                                ->integerNode('third')->isRequired()->min(3)->end()
                                ->integerNode('extra')->isRequired()->min(4)->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
