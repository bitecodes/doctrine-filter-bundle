<?php

namespace BiteCodes\DoctrineFilterBundle\DependencyInjection\CompilePass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class FilterRegistryCompilePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bite_codes.doctrine_filter.filter_registry')) {
            return;
        }

        $definition = $container->getDefinition('bite_codes.doctrine_filter.filter_registry');
        $services = $container->findTaggedServiceIds('bitecodes.doctrine_filter');

        foreach ($services as $serviceId => $tag) {
            $definition->addMethodCall('add', [new Reference($serviceId)]);
        }
    }
}
