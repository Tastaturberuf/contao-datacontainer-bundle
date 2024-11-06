<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Tastaturberuf\ContaoDataContainerBundle\EventListener\DataContainerListener;


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
    ;
};
