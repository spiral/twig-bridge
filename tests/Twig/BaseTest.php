<?php

declare(strict_types=1);

namespace Spiral\Twig\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Spiral\Boot\Bootloader\ConfigurationBootloader;
use Spiral\Boot\BootloadManager\BootloadManager;
use Spiral\Boot\BootloadManager\Initializer;
use Spiral\Boot\Directories;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\Environment;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Config\ConfigManager;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Loader\DirectoryLoader;
use Spiral\Config\Loader\PhpLoader;
use Spiral\Core\ConfigsInterface;
use Spiral\Core\Container;
use Spiral\Twig\Bootloader\TwigBootloader;
use Spiral\Twig\TwigEngine;
use Spiral\Views\ViewManager;
use Spiral\Views\ViewsInterface;

abstract class BaseTest extends TestCase
{
    public const BOOTLOADERS = [
        ConfigurationBootloader::class,
        TwigBootloader::class
    ];

    /** @var Container */
    protected $container;
    /**
     * @var BootloadManager
     */
    protected $app;

    public function setUp(): void
    {
        $this->container = $this->container ?? new Container();
        $this->container->bind(EnvironmentInterface::class, Environment::class);
        $this->container->bind(DirectoriesInterface::class, function () { return new Directories([
            'app'   => __DIR__ . '/../fixtures',
            'cache' => __DIR__ . '/../cache',
            'config' => __DIR__ . '/../config/',
        ]); });

        $this->container->bind(ConfigsInterface::class, ConfiguratorInterface::class);
        $this->container->bind(ConfiguratorInterface::class, function () { return new ConfigManager(
            new DirectoryLoader(__DIR__ . '/../config/', ['php' => $this->container->get(PhpLoader::class)]),
            true
        ); });

        $this->container->bind(ViewsInterface::class, ViewManager::class);

        $this->app = new BootloadManager($this->container, new Initializer($this->container));
        $this->app->bootload(static::BOOTLOADERS);
    }

    protected function getViews(): ViewManager
    {
        return $this->container->get(ViewsInterface::class);
    }

    protected function getTwig(): TwigEngine
    {
        return $this->container->get(ViewsInterface::class)->getEngines()[0];
    }
}
