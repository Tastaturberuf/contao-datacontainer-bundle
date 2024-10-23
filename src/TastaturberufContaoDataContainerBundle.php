<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoDataContainerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tastaturberuf\ContaoDataContainerBundle\DependencyInjection\Compiler\TagServicesByInterfacePass;


class TastaturberufContaoDataContainerBundle extends Bundle
{

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new TagServicesByInterfacePass());
    }

}
