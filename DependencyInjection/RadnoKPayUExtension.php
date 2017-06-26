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

        /** Payments */
        $container->setParameter('radnok_payu_environment', $config['payu_account']['environment']);
        $container->setParameter('radnok_payu_client_id', $config['payu_account']['client_id']);
        $container->setParameter('radnok_payu_client_secret', $config['payu_account']['client_secret']);
        $container->setParameter('radnok_payu_secret_md5', $config['payu_account']['secret_md5']);

        /** Payments */
        $container->setParameter('radnok_payu_payments_single', $config['payments']['single']);
        $container->setParameter('radnok_payu_payments_recurring', $config['payments']['recurring']);

        /** Doctrine */
        $container->setParameter('radnok_payu_model_plan_class', $config['doctrine']['plan_class']);
        $container->setParameter('radnok_payu_model_subscription_class', $config['doctrine']['subscription_class']);
        $container->setParameter('radnok_payu_model_subscriber_class', $config['doctrine']['subscriber_class']);
        $container->setParameter('radnok_payu_model_order_class', $config['doctrine']['order_class']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

    }
}
