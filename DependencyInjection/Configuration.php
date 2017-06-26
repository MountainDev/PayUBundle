<?php

namespace RadnoK\PayUBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root("radnok_payu");

        $rootNode
            ->children()
                ->arrayNode('payu_account')
                    ->children()
                        ->scalarNode('environment')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('client_id')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('client_secret')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('secret_md5')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        $rootNode
            ->children()
                ->arrayNode('payments')
                    ->children()
                        ->scalarNode('single')->defaultTrue()->end()
                        ->scalarNode('recurring')->defaultTrue()->end()
                    ->end()
                ->end()
            ->end()
        ;

        $rootNode
            ->children()
                ->arrayNode('doctrine')
                    ->children()
                        ->scalarNode('plan_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('order_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('subscriber_class')->isRequired()->cannotBeEmpty()->end()
                        ->scalarNode('subscription_class')->isRequired()->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
