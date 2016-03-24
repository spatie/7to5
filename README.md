## WORK IN PROGRESS

# Convert PHP 7 code to PHP 5 code

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/7to5.svg?style=flat-square)](https://packagist.org/packages/spatie/7to5)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/7to5/master.svg?style=flat-square)](https://travis-ci.org/spatie/7to5)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/a671c3c2-b41d-467a-aadd-3e7517df7498.svg?style=flat-square)](https://insight.sensiolabs.com/projects/a671c3c2-b41d-467a-aadd-3e7517df7498)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/7to5.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/7to5)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/7to5.svg?style=flat-square)](https://packagist.org/packages/spatie/7to5)

This package can convert PHP 7 code to PHP 5. This can be handy when you are running PHP 7 in development, but
PHP 5 in productions. In theory you could also convert a PHP 7 only package to PHP 5.

Here's how you can convert a single file.

```php
$converter = new Converter($pathToPhp7Code);

$converter->saveAsPhp5($pathToWherePhp5CodeShouldBeSaved);
```

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Installation

You can install the package via composer:

``` bash
$ composer global require spatie/7to5
```

## Usage

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

You can convert files and directories using a command line command.
If you want to convert only one file and overwrite an existing destination file if it exists:

```bash
$ php7to convert {$sourceFile} {$destinationFile} --overwrite
```

If you want to convert only one file and you are sure that a file with the same name doesn't exists,
or you want to be sure that you don't overwrite it if it exists:

```bash
$ php7to convert {$sourceFile} {$destinationFile}
```
 
If you want to convert all files (not only php files) in a directory and overwrite a existing destination directory:
```bash
$ php7to convert {$sourceDirectory} {$destinationDirectory} --copy-all --overwrite
```

If you want to convert only php files in a directory and overwrite a existing destination directory:
``` bash
$ php7to convert {$sourceDirectory} {$destinationDirectory} --overwrite
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
