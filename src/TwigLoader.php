<?php

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

    private ?ContextInterface $context = null;

    public function __construct(
        private readonly LoaderInterface $loader,
        array $processors
    ) {
        $this->processors = $processors;
    }

    /**
     * Lock loader to specific context.
     */
    public function setContext(ContextInterface $context): void
    {
        $this->context = $context;
    }

    public function getSourceContext(string $name): Source
    {
        if ($this->context === null) {
            throw new EngineException('Unable to use TwigLoader without given context.');
        }

        // Apply processors
        $source = $this->process($this->loader->load($name), $this->context);

        return new Source(
            $source->getCode(),
            \sprintf('%s:%s', $source->getNamespace(), $source->getName()),
            $source->getFilename()
        );
    }

    public function getCacheKey(string $name): string
    {
        if ($this->context === null) {
            throw new EngineException('Unable to use TwigLoader without given context.');
        }

        $filename = $this->loader->load($name)->getFilename();

        return \sprintf('%s.%s', $filename, $this->context->getID());
    }

    public function isFresh(string $name, int $time): bool
    {
        return \filemtime($this->loader->load($name)->getFilename()) < $time;
    }

    public function exists($name)
    {
        return $this->loader->exists($name);
    }
}
