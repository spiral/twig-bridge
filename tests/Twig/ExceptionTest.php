<?php

declare(strict_types=1);

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

use Spiral\Twig\TwigEngine;
use Spiral\Twig\TwigLoader;
use Spiral\Views\ViewContext;
use Spiral\Views\ViewLoader;

class ExceptionTest extends BaseTest
{

    /**
     * @expectedException \Spiral\Views\Exception\EngineException
     */
    public function testNoLoader(): void
    {
        $twig = new TwigEngine(null, []);
        $twig->getLoader();
    }

    /**
     * @expectedException \Spiral\Views\Exception\EngineException
     */
    public function testNoEnvironment(): void
    {
        $twig = new TwigEngine(null, []);
        $twig->getEnvironment(new ViewContext());
    }

    /**
     * @expectedException \Spiral\Views\Exception\EngineException
     */
    public function testLoaderNoContext(): void
    {
        $l = new ViewLoader([]);

        $loader = new TwigLoader($l->withExtension('twig'), []);
        $loader->getSourceContext('test');
    }
}
