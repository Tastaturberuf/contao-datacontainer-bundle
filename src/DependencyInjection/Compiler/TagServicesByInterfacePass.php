<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoDataContainerBundle\DependencyInjection\Compiler;

use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tastaturberuf\ContaoDataContainerBundle\DataContainerInterface;
use Throwable;


class TagServicesByInterfacePass implements CompilerPassInterface
{

    public const TAG = 'tastaturberuf.datacontainer.autoload';


    public function process(ContainerBuilder $container): void
    {
        foreach ($container->getDefinitions() as $definition) {
            $className = $definition->getClass();

            if ($className === null) {
                continue;
            }

            try {
                $reflectionClass = new ReflectionClass($className);

                if ($reflectionClass->implementsInterface(DataContainerInterface::class) && !$definition->hasTag(self::TAG)) {
                    $definition->addTag(self::TAG);
                }
            } catch (Throwable $e) {
                continue;
            }
        }
    }

}
