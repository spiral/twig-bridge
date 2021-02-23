<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Twig\Tests\Twig;

use Spiral\Core\Container\Autowire;
use Spiral\Twig\Bootloader\TwigBootloader;
use Spiral\Twig\Config\TwigConfig;
use Spiral\Views\Processor\ContextProcessor;
use Twig\Extension\CoreExtension;

class ConfigTest extends BaseTest
{
    public function testOptions(): void
    {
        $config = new TwigConfig([
            'options' => ['option' => 'value']
        ]);

        $this->assertSame(['option' => 'value'], $config->getOptions());
    }

    public function testWireConfigString(): void
    {
        $config = new TwigConfig([
            'processors' => [ContextProcessor::class]
        ]);

        $this->assertInstanceOf(
            ContextProcessor::class,
            $config->getProcessors()[0]->resolve($this->container)
        );
    }

    public function testWireConfigExtensions(): void
    {
        $config = new TwigConfig([
            'extensions' => [CoreExtension::class]
        ]);

        $this->assertInstanceOf(
            CoreExtension::class,
            $config->getExtensions()[0]->resolve($this->container)
        );
    }

    public function testWireConfig(): void
    {
        $config = new TwigConfig([
            'processors' => [
                new Autowire(ContextProcessor::class)
            ]
        ]);

        $this->assertInstanceOf(
            ContextProcessor::class,
            $config->getProcessors()[0]->resolve($this->container)
        );
    }

    public function testDebugConfig(): void
    {
        $loader = $this->container->get(TwigBootloader::class);
        $loader->setOption('debug', true);

        $config = $this->container->get(TwigConfig::class);

        $this->assertEquals(true, $config->getOptions()['debug']);
    }
}
