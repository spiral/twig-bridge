<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Twig;

use Spiral\Views\ContextInterface;
use Spiral\Views\Exception\EngineException;
use Spiral\Views\LoaderInterface;
use Spiral\Views\Traits\ProcessorTrait;
use Twig\Loader\LoaderInterface as TwigLoaderInterface;
use Twig\Source;

final class TwigLoader implements TwigLoaderInterface
{
    use ProcessorTrait;

    /** @var LoaderInterface */
    private $loader;

    /** @var ContextInterface */
    private $context;

    /**
     * @param LoaderInterface $loader
     * @param array           $processors
     */
    public function __construct(LoaderInterface $loader, array $processors)
    {
        $this->loader = $loader;
        $this->processors = $processors;
    }

    /**
     * Lock loader to specific context.
     *
     * @param ContextInterface $context
     */
    public function setContext(ContextInterface $context): void
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceContext(string $name): Source
    {
        if (empty($this->context)) {
            throw new EngineException('Unable to use TwigLoader without given context.');
        }

        // Apply processors
        $source = $this->process($this->loader->load($name), $this->context);

        return new Source(
            $source->getCode(),
            sprintf('%s:%s', $source->getNamespace(), $source->getName()),
            $source->getFilename()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheKey(string $name): string
    {
        if (empty($this->context)) {
            throw new EngineException('Unable to use TwigLoader without given context.');
        }

        $filename = $this->loader->load($name)->getFilename();

        return sprintf('%s.%s', $filename, $this->context->getID());
    }

    /**
     * {@inheritdoc}
     */
    public function isFresh(string $name, int $time): bool
    {
        return filemtime($this->loader->load($name)->getFilename()) < $time;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($name)
    {
        return $this->loader->exists($name);
    }
}
