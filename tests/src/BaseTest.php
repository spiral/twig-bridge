<?php

declare(strict_types=1);

namespace Spiral\Twig\Tests;

use Spiral\Boot\BootloadManager\BootloadManager;
use Spiral\Core\Container;
use Spiral\Views\ViewManager;
use Spiral\Views\ViewsInterface;

abstract class BaseTest extends \Spiral\Testing\TestCase
{
    protected Container $container;
    protected BootloadManager $app;

    public function rootDirectory(): string
    {
        return __DIR__ . '/../';
    }

    public function defineBootloaders(): array
    {
        return [
            \Spiral\Boot\Bootloader\ConfigurationBootloader::class,
            \Spiral\Twig\Bootloader\TwigBootloader::class,
        ];
    }

    protected function getViews(): ViewManager
    {
        return $this->container->get(ViewsInterface::class);
    }

    protected function getTwig(): \Spiral\Twig\TwigEngine
    {
        return $this->getContainer()->get(ViewsInterface::class)->getEngines()[0];
    }
}
