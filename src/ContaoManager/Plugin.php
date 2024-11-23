<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoDataContainerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Tastaturberuf\ContaoDataContainerBundle\DependencyInjection\Compiler\TagServicesByInterfacePass;
use Tastaturberuf\ContaoDataContainerBundle\TastaturberufContaoDataContainerBundle;


class Plugin implements BundlePluginInterface
{

    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(TastaturberufContaoDataContainerBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class])
        ];
    }

}
