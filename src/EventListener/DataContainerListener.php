<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoDataContainerBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\DcaExtractor;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Tastaturberuf\ContaoDataContainerBundle\DataContainerInterface;
use Tastaturberuf\ContaoDataContainerBundle\DependencyInjection\Compiler\TagServicesByInterfacePass;


final class DataContainerListener
{

    /**
     * @param DataContainerInterface[] $configs
     */
    private array $configs = [];


    public function __construct(
        #[TaggedIterator('tastaturberuf.datacontainer.autoload')] iterable $configs
    )
    {
        foreach ($configs as $config) {
            if ($config instanceof DataContainerInterface) {
                $this->configs[$config->getTable()] = $config;
            }
        }
    }

    /**
     * Since we only generate the DCA configuration in hooks, DCA files are no longer created. This means that the DCAs
     * are no longer loaded automatically, as they are loaded using the files and only then is the hook executed.
     */
    #[AsHook('sqlGetFromDca', priority: PHP_INT_MAX)]
    public function sqlGetFromDca(array $sql): array
    {
        foreach ($this->configs as $config) {
            $table = $config->getTable();

            $extract = DcaExtractor::getInstance($table);

            if ($extract->isDbTable()) {
                $sql[$table] = $extract->getDbInstallerArray();
            }
        }

        return $sql;
    }

    #[AsHook('loadDataContainer', priority: PHP_INT_MAX)]
    public function loadDataContainer(string $table): void
    {
        if (!array_key_exists($table, $this->configs)) {
            return;
        }

        // the replacements come after because it may be overridden in classic DCA files like `contao/dca/tl_*.php`
        $GLOBALS['TL_DCA'][$table] = array_replace_recursive($this->configs[$table]->getConfig(), $GLOBALS['TL_DCA'][$table] ?? []);
    }

}
