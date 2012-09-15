<?php

namespace Ricbra\Bundle\DiscogsBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('ricbra_discogs');
        $rootNode
            ->children()
                ->scalarNode('host')
                    ->defaultValue('api.discogs.com')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('items_per_page')
                    ->defaultValue(50)
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('identifier')
                    ->defaultValue('DiscogsApi/0.1 +https://github.com/ricbra/php-discogs-api')
                ->end()
                ->scalarNode('throttle')
                    ->defaultTrue()
                    ->cannotBeEmpty()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
