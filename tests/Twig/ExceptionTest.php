<?php

declare(strict_types=1);

namespace Spiral\Twig\Tests\Twig;

use Spiral\Twig\TwigEngine;
use Spiral\Twig\TwigLoader;
use Spiral\Views\Exception\EngineException;
use Spiral\Views\ViewContext;
use Spiral\Views\ViewLoader;

class ExceptionTest extends BaseTest
{
    public function testNoLoader(): void
    {
        $this->expectException(EngineException::class);

        $twig = new TwigEngine(null, []);
        $twig->getLoader();
    }

    public function testNoEnvironment(): void
    {
        $this->expectException(EngineException::class);

        $twig = new TwigEngine(null, []);
        $twig->getEnvironment(new ViewContext());
    }

    public function testLoaderNoContext(): void
    {
        $this->expectException(EngineException::class);

        $l = new ViewLoader([]);

        $loader = new TwigLoader($l->withExtension('twig'), []);
        $loader->getSourceContext('test');
    }
}
