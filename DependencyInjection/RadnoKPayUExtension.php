<?php

namespace RadnoK\PayUBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class RadnoKPayUExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $plan = $config['doctrine']['plan_class'];
        $container->setParameter('radnok_model_plan_class', $plan);

        $subscription = $config['doctrine']['subscription_class'];
        $container->setParameter('radnok_model_subscription_class', $subscription);

        $subscriber = $config['doctrine']['subscriber_class'];
        $container->setParameter('radnok_model_subscriber_class', $subscriber);

        $order = $config['doctrine']['order_class'];
        $container->setParameter('radnok_model_order_class', $order);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

    }
}
