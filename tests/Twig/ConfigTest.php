<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

use Spiral\Core\Container\Autowire;
use Spiral\Twig\Config\TwigConfig;
use Spiral\Views\Processor\ContextProcessor;
use Twig\Extension\CoreExtension;

class ConfigTest extends BaseTest
{
    public function testOptions()
    {
        $config = new TwigConfig([
            'options' => ['option' => 'value']
        ]);

        $this->assertSame(['option' => 'value'], $config->getOptions());
    }

    public function testWireConfigString()
    {
        $config = new TwigConfig([
            'processors' => [ContextProcessor::class]
        ]);

        $this->assertInstanceOf(
            ContextProcessor::class,
            $config->getProcessors()[0]->resolve($this->container)
        );
    }

    public function testWireConfigExtensions()
    {
        $config = new TwigConfig([
            'extensions' => [CoreExtension::class]
        ]);

        $this->assertInstanceOf(
            CoreExtension::class,
            $config->getExtensions()[0]->resolve($this->container)
        );
    }

    public function testWireConfig()
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
}