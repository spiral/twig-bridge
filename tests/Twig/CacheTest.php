<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Twig\Tests\Twig;

use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\PatchInterface;
use Spiral\Files\Files;
use Spiral\Files\FilesInterface;
use Spiral\Twig\TwigCache;
use Spiral\Views\ViewContext;

class CacheTest extends BaseTest
{
    /** @var FilesInterface */
    protected $files;

    public function setUp(): void
    {
        parent::setUp();

        $this->files = new Files();

        /** @var ConfiguratorInterface $configurator */
        $configurator = $this->container->get(ConfiguratorInterface::class);
        $configurator->modify('views', new EnableCachePatch());
    }

    public function testCache(): void
    {
        $this->assertCount(0, $this->files->getFiles(__DIR__ . '/../cache/', '*.php'));

        $twig = $this->getTwig();
        $this->assertSame('test', $twig->get('test', new ViewContext())->render([]));
        $this->assertCount(1, $this->files->getFiles(__DIR__ . '/../cache/', '*.php'));

        $twig->reset('test', new ViewContext());
        $this->assertCount(0, $this->files->getFiles(__DIR__ . '/../cache/', '*.php'));

        $cache = new TwigCache(__DIR__ . '/../cache/');
        $this->assertNotSame(0, $cache->getTimestamp(__DIR__ . '/../cache/' . '.empty'));
        $this->assertSame(0, $cache->getTimestamp(__DIR__ . '/../cache/' . '.other'));
    }
}
