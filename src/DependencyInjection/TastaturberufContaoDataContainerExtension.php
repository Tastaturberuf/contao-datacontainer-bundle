<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoDataContainerBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Tastaturberuf\ContaoDataContainerBundle\DataContainerInterface;
use Tastaturberuf\ContaoDataContainerBundle\DependencyInjection\Compiler\TagServicesByInterfacePass;


class TastaturberufContaoDataContainerExtension extends Extension
{

    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('services.php');

        $container->registerForAutoconfiguration(DataContainerInterface::class)
            ->addTag('tastaturberuf.datacontainer.autoload');
    }

}
