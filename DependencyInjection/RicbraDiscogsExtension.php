<?php

namespace Ricbra\Bundle\DiscogsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class RicbraDiscogsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $params = [
            'defaults' => [
                'headers' => ['User-Agent' => $config['user_agent']]
            ],
            'emitter' => new Reference('ricbra_discogs.emitter')
        ];

        $emitterDefinition = $container->getDefinition('ricbra_discogs.emitter');
        $clientDefinition = $container->getDefinition('discogs');
        $clientDefinition->replaceArgument(0, $params);

        if ($config['throttle']['enabled']) {
            $throttleDefinition = $container->getDefinition('ricbra_discogs.subscriber.throttle_subscriber');
            $throttleDefinition->replaceArgument(0, $config['throttle']['microseconds']);

            $emitterDefinition->addMethodCall('attach', [new Reference('ricbra_discogs.subscriber.throttle_subscriber')]);
        }

        if ($config['oauth']['enabled']) {
            $loader->load('oauth.xml');

            $subscriber = $container->getDefinition('ricbra_discogs.subscriber.oauth');
            $subscriber->replaceArgument(0, $config['oauth']['consumer_key']);
            $subscriber->replaceArgument(1, $config['oauth']['consumer_secret']);

            $factory = $container->getDefinition('ricbra_discogs.oauth_subscriber_factory');
            $factory->replaceArgument(0, new Reference($config['oauth']['token_provider_id']));
            $emitterDefinition->addMethodCall('attach', [new Reference('ricbra_discogs.subscriber.oauth')]);
        }
    }
}
