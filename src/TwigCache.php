<?php

declare(strict_types=1);

namespace Spiral\Twig;

use Spiral\Files\FilesInterface;
use Twig\Cache\CacheInterface as TwigCacheInterface;

final class TwigCache implements TwigCacheInterface
{
    public function __construct(
        private readonly string $directory,
        private readonly ?FilesInterface $files = null
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function generateKey(string $name, string $className): string
    {
        $prefix = \sprintf('%s:%s', $name, $className);
        $prefix = \preg_replace('/([^A-Za-z0-9]|-)+/', '-', $prefix);

        return \sprintf('%s/%s.php', \rtrim($this->directory, '/') . '/', $prefix);
    }

    /**
     * Delete cached files.
     *
     * @param string $name
     * @param class-string $className
     */
    public function delete(string $name, string $className): void
    {
        try {
            $this->files->delete($this->generateKey($name, $className));
        } catch (\Throwable $e) {
        }
    }

    /**
     * {@inheritdoc}
     */
    public function write(string $key, string $content): void
    {
        $this->files->write($key, $content, FilesInterface::RUNTIME, true);
    }

    /**
     * {@inheritdoc}
     */
    public function load(string $key): void
    {
        if ($this->files->exists($key)) {
            include_once $key;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp(string $key): int
    {
        if ($this->files->exists($key)) {
            return $this->files->time($key);
        }

        return 0;
    }
}
