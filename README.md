# Spiral Framework: Twig Adapter
[![Latest Stable Version](https://poser.pugx.org/spiral/twig-bridge/version)](https://packagist.org/packages/spiral/twig-bridge)
[![CI Status](https://github.com/spiral/twig-bridge/workflows/Testing/badge.svg)](https://github.com/spiral/twig-bridge/actions)
[![Codecov](https://codecov.io/gh/spiral/twig-bridge/branch/master/graph/badge.svg)](https://codecov.io/gh/spiral/twig-bridge/)

## Installation
The extension requires `spiral/views` package.

```
$ composer require spiral/twig-bridge
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

> Following methods are available `setOption`, `addExtension`, `addProcessor`.
