<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoDataContainerBundle\ContaoManager;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ConfigPluginInterface;
use Contao\ManagerPlugin\Config\ContainerBuilder;
use Symfony\Component\Config\Loader\LoaderInterface;
use Tastaturberuf\ContaoDataContainerBundle\DependencyInjection\Compiler\TagServicesByInterfacePass;
use Tastaturberuf\ContaoDataContainerBundle\TastaturberufContaoDataContainerBundle;


class Plugin implements BundlePluginInterface, ConfigPluginInterface
{

    public function getBundles(ParserInterface $parser): array
    {
        return [BundleConfig::create(TastaturberufContaoDataContainerBundle::class)];
    }

    public function registerContainerConfiguration(LoaderInterface $loader, array $managerConfig): void
    {
        $loader->load(static function (ContainerBuilder $container): void {
            $container->addCompilerPass(new TagServicesByInterfacePass());
        });
    }

}
