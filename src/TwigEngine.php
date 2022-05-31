<?php

declare(strict_types=1);

namespace Spiral\Twig;

use Spiral\Twig\Exception\SyntaxException;
use Spiral\Views\ContextInterface;
use Spiral\Views\EngineInterface;
use Spiral\Views\Exception\EngineException;
use Spiral\Views\LoaderInterface;
use Spiral\Views\ProcessorInterface;
use Spiral\Views\ViewInterface;
use Twig\Environment;
use Twig\Error\SyntaxError;
use Twig\Extension\ExtensionInterface;
use Twig\TemplateWrapper;

final class TwigEngine implements EngineInterface
{
    protected const EXTENSION = 'twig';

    private ?LoaderInterface $loader = null;
    private ?Environment $environment = null;

    /**
     * @param ExtensionInterface[] $extensions
     * @param ProcessorInterface[] $processors
     */
    public function __construct(
        private readonly ?TwigCache $cache = null,
        private readonly array $options = [],
        private readonly array $extensions = [],
        private readonly array $processors = []
    ) {
    }

    public function withLoader(LoaderInterface $loader): EngineInterface
    {
        $engine = clone $this;
        $engine->loader = $loader->withExtension(static::EXTENSION);

        $engine->environment = new Environment(
            new TwigLoader($engine->loader, $this->processors),
            $this->options
        );

        $engine->environment->setCache($this->cache instanceof TwigCache ? $this->cache : false);
        foreach ($this->extensions as $extension) {
            $engine->environment->addExtension($extension);
        }

        return $engine;
    }

    public function getLoader(): LoaderInterface
    {
        if ($this->loader === null) {
            throw new EngineException('No associated loader found');
        }

        return $this->loader;
    }

    /**
     * Return environment locked to specific context.
     */
    public function getEnvironment(ContextInterface $context): Environment
    {
        if ($this->environment === null) {
            throw new EngineException('No associated environment found.');
        }

        $this->environment->getLoader()->setContext($context);

        return $this->environment;
    }

    public function compile(string $path, ContextInterface $context): TemplateWrapper
    {
        try {
            return $this->getEnvironment($context)->load($this->normalize($path));
        } catch (SyntaxError $exception) {
            //Let's clarify exception location
            throw SyntaxException::fromTwig($exception);
        }
    }

    public function reset(string $path, ContextInterface $context): void
    {
        $path = $this->normalize($path);

        if ($this->cache !== null) {
            $this->cache->delete($path, $this->getEnvironment($context)->getTemplateClass($path));
        }
    }

    public function get(string $path, ContextInterface $context): ViewInterface
    {
        return new TwigView($this->compile($path, $context));
    }

    protected function normalize(string $path): string
    {
        $path = $this->getLoader()->load($path);

        return \sprintf('%s:%s', $path->getNamespace(), $path->getName());
    }
}
