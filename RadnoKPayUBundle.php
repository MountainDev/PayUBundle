<?php

namespace RadnoK\PayUBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use RadnoK\PayUBundle\DependencyInjection\Compiler\DoctrineResolveTargetEntityPass;

class RadnoKPayUBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(
            new DoctrineResolveTargetEntityPass(),
            PassConfig::TYPE_BEFORE_OPTIMIZATION,
            1000
        );
    }
}
