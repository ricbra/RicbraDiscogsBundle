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
                ->scalarNode('user_agent')
                    ->defaultValue('RicBraDiscogsBundle/1.0 +https://github.com/ricbra/php-discogs-api')
                ->end()
                ->arrayNode('throttle')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultTrue()
                        ->end()
                        ->integerNode('microseconds')
                            ->defaultValue(1000000)
                        ->end()
                     ->end()
                ->end()
                ->arrayNode('oauth')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('enabled')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('consumer_key')->end()
                        ->scalarNode('consumer_secret')->end()
                        ->scalarNode('token_provider_id')->end()
                    ->end()
                    ->validate()
                        ->ifTrue(function($a) {
                            $enabled = $a['enabled'];
                            $key = isset($a['consumer_key']) && $a['consumer_key'];
                            $secret = isset($a['consumer_secret']) && $a['consumer_secret'];
                            $token = isset($a['token_provider_id']) && $a['token_provider_id'];

                            return $enabled && (! $key || ! $secret || ! $token);
                        })
                        ->thenInvalid('The option "ricbra_discogs.oauth.consumer_key", "ricbra_discogs.oauth.consumer_secret" and "ricbra_discogs.oauth.token_provider_id" are required')
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
