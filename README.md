## WORK IN PROGRESS

# Convert PHP 7 code to PHP 5 code

[![Latest Version on Packagist](https://img.shields.io/packagist/v/spatie/7to5.svg?style=flat-square)](https://packagist.org/packages/spatie/7to5)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/spatie/7to5/master.svg?style=flat-square)](https://travis-ci.org/spatie/7to5)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/a671c3c2-b41d-467a-aadd-3e7517df7498.svg?style=flat-square)](https://insight.sensiolabs.com/projects/a671c3c2-b41d-467a-aadd-3e7517df7498)
[![Quality Score](https://img.shields.io/scrutinizer/g/spatie/7to5.svg?style=flat-square)](https://scrutinizer-ci.com/g/spatie/7to5)
[![Total Downloads](https://img.shields.io/packagist/dt/spatie/7to5.svg?style=flat-square)](https://packagist.org/packages/spatie/7to5)

This package can convert PHP 7 code to PHP 5. Here's how you can convert a single file

```php
```

Spatie is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Install

**Note:** Remove this paragraph if you are building a public package
This package is custom built for [Spatie](https://spatie.be) projects and is therefore not registered on packagist. 
In order to install it via composer you must specify this extra repository in `composer.json`:

```json
"repositories": [ { "type": "composer", "url": "https://satis.spatie.be/" } ]
```

You can install the package via composer:
``` bash
$ composer require spatie/7to5
```

## Usage

``` php
$converter = new Spatie\Php7to5\Converter($pathToPhp7File);
$converter->saveAsPhp5($pathWherePhp5CodeShouldBeSaved);
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
