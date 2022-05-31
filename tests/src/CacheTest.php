<?php

declare(strict_types=1);

namespace Spiral\Twig\Tests;

use Spiral\Config\ConfiguratorInterface;
use Spiral\Files\Files;
use Spiral\Files\FilesInterface;
use Spiral\Twig\TwigCache;
use Spiral\Views\Config\ViewsConfig;
use Spiral\Views\ViewContext;

class CacheTest extends BaseTest
{
    protected FilesInterface $files;

    public function setUp(): void
    {
        parent::setUp();

        $this->files = new Files();

        /** @var ConfiguratorInterface $configurator */
        $configurator = $this->getContainer()->get(ConfiguratorInterface::class);
        $configurator->modify(ViewsConfig::CONFIG, new EnableCachePatch());
    }

    public function testCache(): void
    {
        $cacheDir = __DIR__.'/../app/runtime/cache/';
        $this->assertCount(0, $this->files->getFiles($cacheDir, '*.php'));

        $twig = $this->getTwig();

        $this->assertSame('test', $twig->get('test', new ViewContext())->render([]));
        $this->assertCount(1, $this->files->getFiles($cacheDir, '*.php'));

        $twig->reset('test', new ViewContext());
        $this->assertCount(0, $this->files->getFiles($cacheDir, '*.php'));

        $cache = new TwigCache($cacheDir);
        $this->assertNotSame(0, $cache->getTimestamp($cacheDir. '.empty'));
        $this->assertSame(0, $cache->getTimestamp($cacheDir. '.other'));
    }
}
