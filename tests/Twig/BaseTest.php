<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Spiral\Boot\BootloadManager;
use Spiral\Boot\Directories;
use Spiral\Boot\DirectoriesInterface;
use Spiral\Boot\Environment;
use Spiral\Boot\EnvironmentInterface;
use Spiral\Config\ConfigManager;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Loader\DirectoryLoader;
use Spiral\Core\ConfigsInterface;
use Spiral\Core\Container;
use Spiral\Twig\Bootloader\TwigBootloader;
use Spiral\Twig\TwigEngine;
use Spiral\Views\ViewManager;
use Spiral\Views\ViewsInterface;

abstract class BaseTest extends TestCase
{
    /** @var Container */
    protected $container;

    const BOOTLOADERS = [TwigBootloader::class];

    public function setUp()
    {
        $this->container = $this->container ?? new Container();
        $this->container->bind(EnvironmentInterface::class, new Environment());
        $this->container->bind(DirectoriesInterface::class, new Directories([
            'app'   => __DIR__ . '/../fixtures',
            'cache' => __DIR__ . '/../cache'
        ]));

        $this->container->bind(ConfigsInterface::class, ConfiguratorInterface::class);
        $this->container->bind(ConfiguratorInterface::class, new ConfigManager(
            new DirectoryLoader(__DIR__ . '/../config/', $this->container),
            true
        ));

        $this->container->bind(ViewsInterface::class, ViewManager::class);

        (new BootloadManager($this->container))->bootload(static::BOOTLOADERS);
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