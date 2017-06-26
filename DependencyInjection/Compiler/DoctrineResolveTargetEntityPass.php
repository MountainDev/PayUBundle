<?php
/**
 * Created by radnok <alfaro.konrad@gmail.com>.
 * Date: 26.06.2017
 */

namespace RadnoK\PayUBundle\DependencyInjection\Compiler;

use Doctrine\ORM\Version;
use RadnoK\PayUBundle\Model\OrderInterface;
use RadnoK\PayUBundle\Model\PlanInterface;
use RadnoK\PayUBundle\Model\SubscriberInterface;
use RadnoK\PayUBundle\Model\SubscriptionInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoctrineResolveTargetEntityPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->findDefinition('doctrine.orm.listeners.resolve_target_entity');

        $definition->addMethodCall('addResolveTargetEntity', [
            OrderInterface::class, $container->getParameter('radnok_payu_model_order_class'), []
        ]);

        if ($container->getParameter('radnok_payu_payments_recurring')) {
            $definition->addMethodCall('addResolveTargetEntity', [
                PlanInterface::class, $container->getParameter('radnok_payu_model_plan_class'), []
            ]);

            $definition->addMethodCall('addResolveTargetEntity', [
                SubscriptionInterface::class, $container->getParameter('radnok_payu_model_subscription_class'), []
            ]);

            $definition->addMethodCall('addResolveTargetEntity', [
                SubscriberInterface::class, $container->getParameter('radnok_payu_model_subscriber_class'), []
            ]);
        }

        if (version_compare(Version::VERSION, '2.5.0-DEV') < 0) {
            $definition->addTag('doctrine.event_listener', ['event' => 'loadClassMetadata']);
        } else {
            $definition->addTag('doctrine.event_subscriber', ['connection' => 'default']);
        }
    }
}
