# Convert PHP 7 code to PHP 5 code

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/7to5.svg?style=flat-square)](https://packagist.org/packages/spatie/7to5)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/7to5/master.svg?style=flat-square)](https://travis-ci.org/spatie/7to5)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/7to5.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/7to5)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/7to5.svg?style=flat-square)](https://packagist.org/packages/spatie/7to5)

This package can convert PHP 7 code to PHP 5. This can be handy when you are running PHP 7 in development, but
PHP 5 in production.

You can convert an entire directory with PHP 7 code with a the console command:

```bash
$ php7to5 convert {$directoryWithPHP7Code} {$destinationWithPHP5Code}
```

Here's an example of what it can do. It'll convert this code with PHP 7 features:
```php
class Test
{
    public function test()
    {
        $class = new class() {
            public function method(string $parameter = '') : string {
                return $parameter ?? 'no parameter set';
            }
        };
        
        $class->method();

        $anotherClass = new class() {
            public function anotherMethod(int $integer) : int {
                return $integer > 3;
            }
        };
    }
            
}

```

to this equivalent PHP 5 code:

```php 
class AnonymousClass0
{
    public function method($parameter = '')
    {
        return isset($parameter) ? $parameter : 'no parameter set';
    }
}
class AnonymousClass1
{
    public function anotherMethod($integer)
    {
        return $integer < 3 ? -1 : ($integer == 3 ? 0 : 1);
    }
}
class Test
{
    public function test()
    {
        $class = new AnonymousClass0();
        $class->method();
        $anotherClass = new AnonymousClass1();
    }
}
```

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Postcardware

You're free to use this package (it's [MIT-licensed](LICENSE.md)), but if it makes it to your production environment we highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using.

Our address is: Spatie, Samberstraat 69D, 2060 Antwerp, Belgium.

All postcards are published [on our website](https://spatie.be/en/opensource/postcards).

## Installation

If you plan on use [the console command](#using-the-console-command) we recommend installing the package globally:

``` bash
$ composer global require spatie/7to5
```

If you want to [integrate the package in your own code](#programmatically-convert-files) require the package like usual:

``` bash
$ composer require spatie/7to5
```

## The conversion process

This package converts PHP 7 code to equivalent PHP 5 code by:

- removing scalar type hints
- removing return type hints
- removing the strict type declaration
- replacing the spaceship operator by an equivalent PHP 5 code
- replacing null coalesce statements by equivalent PHP 5 code
- replacing group use declarations by equivalent PHP 5 code
- replacing defined arrays by equivalent PHP 5 code
- converting anonymous classes to regular classes

Because there are a lot of things that cannot be detected and/or converted properly we do not guarantee that the converted code will work. We highly recommend running your automated tests against the converted code to determine if it works.

## Using the console command

This package provides a console command `php7to5` to convert files and directories.

This is how a entire directory can be converted:

```bash
$ php7to5 convert {$directoryWithPHP7Code} {$destinationWithPHP5Code}
```

Want to convert a single file? That's cool too! You can use the same command.

```bash
$ php7to5 convert {$sourceFileWithPHP7Code} {$destinationFileWithPHP5Code}
```

By default the command will only copy over `php`-files. Want to copy over all files? Use the `copy-all` option:
 
```bash
$ php7to5 convert {$directoryWithPHP7Code} {$destinationWithPHP5Code} --copy-all
```

## Programmatically convert files

You can convert a single file by running this code:

```php
$converter = new Converter($pathToPhp7Code);

$converter->saveAsPhp5($pathToWherePhp5CodeShouldBeSaved);
```

An entire directory can be converted as well:

```php 
$converter = new DirectoryConverter($sourceDirectory);

$converter->savePhp5FilesTo($destinationDirectory);
```

By default this will recursively copy all to files to the destination directory, even the non php files.

If you only want to copy over the php files do this:

```php 
$converter = new DirectoryConverter($sourceDirectory);

$converter
   ->doNotCopyNonPhpFiles()
   ->savePhp5FilesTo($destinationDirectory);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@spatie.be instead of using the issue tracker.

## Credits

- [Hannes Van De Vreken](https://twitter.com/hannesvdvreken)
- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

Original idea: [Jens Segers](https://twitter.com/jenssegers)

## About Spatie
Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
