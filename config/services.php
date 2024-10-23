<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Tastaturberuf\ContaoDataContainerBundle\DependencyInjection\Compiler\TagServicesByInterfacePass;
use Tastaturberuf\ContaoDataContainerBundle\EventListener\DataContainerListener;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;


/**
 * @Formatter:off
 */
return static function(ContainerConfigurator $container)
{
    $container->services()

        ->defaults()
            ->autowire()
            ->autoconfigure()

        ->set(DataContainerListener::class)
            ->args([tagged_iterator(TagServicesByInterfacePass::TAG)])

    ;
};
