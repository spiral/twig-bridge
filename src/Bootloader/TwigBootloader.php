<?php

declare(strict_types=1);

namespace Spiral\Twig\Bootloader;

use Psr\Container\ContainerInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\Append;
use Spiral\Core\Container\Autowire;
use Spiral\Core\FactoryInterface;
use Spiral\Translator\Views\LocaleProcessor;
use Spiral\Twig\Config\TwigConfig;
use Spiral\Twig\Extension\ContainerExtension;
use Spiral\Twig\TwigCache;
use Spiral\Twig\TwigEngine;
use Spiral\Views\Bootloader\ViewsBootloader;
use Spiral\Views\Config\ViewsConfig;
use Spiral\Views\Processor\ContextProcessor;

final class TwigBootloader extends Bootloader
{
    protected const SINGLETONS = [
        TwigEngine::class => [self::class, 'twigEngine']
    ];

    public function __construct(
        private readonly ConfiguratorInterface $config
    ) {
    }

    public function init(ContainerInterface $container, ViewsBootloader $views): void
    {
        $this->config->setDefaults(TwigConfig::CONFIG, [
            'options'    => [],
            'extensions' => [ContainerExtension::class],
            'processors' => [ContextProcessor::class]
        ]);

        $views->addEngine(TwigEngine::class);

        if ($container->has(LocaleProcessor::class)) {
            $this->addProcessor(LocaleProcessor::class);
        }
    }

    public function setOption(string $key, mixed $value): void
    {
        $this->config->modify(TwigConfig::CONFIG, new Append('options', $key, $value));
    }

    public function addExtension(mixed $extension): void
    {
        $this->config->modify(TwigConfig::CONFIG, new Append('extensions', null, $extension));
    }

    public function addProcessor(mixed $processor): void
    {
        $this->config->modify(TwigConfig::CONFIG, new Append('processors', null, $processor));
    }

    private function twigEngine(
        TwigConfig $config,
        ViewsConfig $viewConfig,
        FactoryInterface $factory
    ): TwigEngine {
        $extensions = [];
        foreach ($config->getExtensions() as $extension) {
            if ($extension instanceof Autowire) {
                $extension = $extension->resolve($factory);
            }

            $extensions[] = $extension;
        }

        $processors = [];
        foreach ($config->getProcessors() as $processor) {
            if ($processor instanceof Autowire) {
                $processor = $processor->resolve($factory);
            }

            $processors[] = $processor;
        }

        $cache = null;
        if ($viewConfig->isCacheEnabled()) {
            $cache = new TwigCache($viewConfig->getCacheDirectory());
        }

        return new TwigEngine($cache, $config->getOptions(), $extensions, $processors);
    }
}
