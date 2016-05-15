<?php

namespace BiteCodes\DoctrineFilterBundle;

use BiteCodes\DoctrineFilterBundle\DependencyInjection\CompilePass\FilterRegistryCompilePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BiteCodesDoctrineFilterBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new FilterRegistryCompilePass());
    }

}
