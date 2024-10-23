<?php

declare(strict_types=1);

namespace Tastaturberuf\ContaoDataContainerBundle;

interface DataContainerInterface
{

    public function getTable(): string;

    public function getConfig(): array;

}
