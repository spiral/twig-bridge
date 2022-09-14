# Spiral Framework: Twig Adapter

[![PHP Version Require](https://poser.pugx.org/spiral/twig-bridge/require/php)](https://packagist.org/packages/spiral/twig-bridge)
[![Latest Stable Version](https://poser.pugx.org/spiral/twig-bridge/v/stable)](https://packagist.org/packages/spiral/twig-bridge)
[![phpunit](https://github.com/spiral/twig-bridge/actions/workflows/phpunit.yml/badge.svg)](https://github.com/spiral/twig-bridge/actions)
[![psalm](https://github.com/spiral/twig-bridge/actions/workflows/psalm.yml/badge.svg)](https://github.com/spiral/twig-bridge/actions)
[![Codecov](https://codecov.io/gh/spiral/twig-bridge/branch/master/graph/badge.svg)](https://codecov.io/gh/spiral/twig-bridge/)
[![Total Downloads](https://poser.pugx.org/spiral/twig-bridge/downloads)](https://packagist.org/packages/spiral/twig-bridge)
[![type-coverage](https://shepherd.dev/github/spiral/twig-bridge/coverage.svg)](https://shepherd.dev/github/spiral/twig-bridge)
[![psalm-level](https://shepherd.dev/github/spiral/twig-bridge/level.svg)](https://shepherd.dev/github/spiral/twig-bridge)

<b>[Documentation](https://spiral.dev/docs/views-twig)</b> | [Framework Bundle](https://github.com/spiral/framework)

## Installation

The extension requires `spiral/views` package.

```
composer require spiral/twig-bridge
```

To enable extension modify your application by adding `Spiral\Twig\Bootloader\TwigBootloader`:

```php

class App extends Kernel
{
    /*
     * List of components and extensions to be automatically registered
     * within system container on application start.
     */
    protected const LOAD = [
        // ...
        
        Spiral\Twig\Bootloader\TwigBootloader::class,
    ];
}
```

## Configuration

You can enable any custom twig extension by requesting `Spiral\Twig\TwigEngine` in your bootloaders:

```php
class TwigExtensionBootloader extends Bootloader 
{
    public function boot(TwigEngine $engine)
    {
        $engine->addExtension(new Extension());
    }
}
```

## Lazy Configuration

To configure TwigEngine on demand use functionality provided by `TwigBootloader`:

```php
class TwigExtensionBootloader extends Bootloader 
{
    public function boot(TwigBootloader $twig)
    {
        $twig->addExtension('container.binding');
    }
}
```
> **Note**
> Following methods are available `setOption`, `addExtension`, `addProcessor`.

## License:

MIT License (MIT). Please see [`LICENSE`](./LICENSE) for more information. Maintained by [Spiral Scout](https://spiralscout.com).

