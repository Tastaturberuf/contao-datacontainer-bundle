[![Version](http://img.shields.io/packagist/v/tastaturberuf/contao-datacontainer-bundle)](https://packagist.org/packages/tastaturberuf/contao-datacontainer-bundle)
[![Contao Version](https://img.shields.io/badge/contao--version-^4.13_||_^5.3-%23F47C00)](https://contao.org)

# From Data Container Array to Data Container Class

Define data container configuration with classes/services instead of plain PHP files.

## Why?

- Real Dependency Injection
- Don't deal with DCA files and wild names because of the prefixes like `tl_vendor_bundle_tablename`. Just define your
  table names one time and forget about it later. See advanced usage below.
- Define private callbacks and logic methods in class scope like in Contao 2 or 3.
- Define whatever you need, it's your class.
- It's still possible to override definitions with dca files.

## How?
A compiler pass tag all classes which implement `Tastaturberuf\ContaoDataContainerBundle\DataContainerInterface` with the tag `tastaturberuf.datacontainer.autoload`.
An event listener with the iterable classes listen to the `loadDataContainer` hook and recursive merge the array if the table name matches.

Litte bit of magic:
On migrations the table name is unknown if the are no DCA files present. The hook `sqlGetFromDca` take care that the definitions get loaded properly.

All logic happen in this file: https://github.com/Tastaturberuf/contao-datacontainer-bundle/blob/main/src/EventListener/DataContainerListener.php

## Install

### via Composer

```
composer require tastaturberuf/contao-datacontainer-bundle
```

## Basic usage

Define a class and implement `DataContainerInterface`.

```php
<?php

declare(strict_types=1);

namespace App;

use Contao\DC_Table;
use Tastaturberuf\ContaoDataContainerBundle\DataContainerInterface;


class DataContainer implements DataContainerInterface
{

    // return the table name
    public function getTable(): string
    {
        return 'tl_data_container';
    }

    // return the DCA config
    public function getConfig(): array
    {
        return [
            'config' => [
                'container' => DC_Table::class
            ]
            // ...
        ];
    }

}
```

## Advanced usage

```php
<?php

declare(strict_types=1);

namespace App;

use Ausi\SlugGenerator\SlugGenerator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCallback;
use Contao\CoreBundle\Intl\Countries;
use Contao\DC_Table;
use Contao\Model;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Tastaturberuf\ContaoDataContainerBundle\DataContainerInterface;


// App/Contao/Model/DataContainerModel.php
class DataContainerModel extends Model
{
    // Every time you need the table name use this public constant!
    public const TABLE = 'tl_data_container';

    protected static $strTable = self::TABLE;
    
    //...
}

// App/Contao/DataContainer/MyDataContainer.php
class MyDataContainer implements DataContainerInterface
{
    /**
     * Now you can use real dependency injection in DCC 
     */
    public function __construct(
        private readonly SlugGenerator $slugGenerator,
        private readonly Countries $countries,
        #[Autowire(param: 'contao.image.valid_extensions')] // since Contao 5 / Symfony 6 
        privare readonly string $validImages
    ) {}

    public function getTable(): string
    {
        // DRY: Use the model constant here
        return DataContainerModel::TABLE;
    }

    /**
     * Return the same array you would do in `dca/tl_i_hate_remember_prefixes_table.php`
     */
    public function getConfig(): array
    {
        return [
            'config' => [
                'container' => DC_Table::class
            ]
            // ...
            'fields' => [
                'alias' =>  [
                    'inputType' => 'text',
                    'save_callback' => $this->saveCallback(...)
                    //...
                ],
                'country' => [
                    'inputType' => 'select'
                    //...
                ]
            ]
        ];
    }
    
    
    /**
     * You can define private callbacks
     */
    private function saveCallback(string $value): string
    {
        return $this->slugGenerator->generate($value);
    }
    
    /**
     * You can define public callbacks (and may use the TABLE constant from the model)
     */
    #[AsCallback(DataContainerModel::TABLE, 'fields.country.options')]
    public function publicCallback(): array 
    {
        return $this->countries->getCountries();
    }

}
```

## Maintainer

[Tastaturberuf](https://tastaturberuf.de) **with ♥ and Contao**
