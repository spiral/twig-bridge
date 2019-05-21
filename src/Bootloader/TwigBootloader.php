<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Twig\Bootloader;

use Psr\Container\ContainerInterface;
use Spiral\Boot\Bootloader\Bootloader;
use Spiral\Boot\Bootloader\DependedInterface;
use Spiral\Bootloader\Views\ViewsBootloader;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\Append;
use Spiral\Core\Container\Autowire;
use Spiral\Core\FactoryInterface;
use Spiral\Translator\Views\LocaleProcessor;
use Spiral\Twig\Config\TwigConfig;
use Spiral\Twig\Extension\ContainerExtension;
use Spiral\Twig\TwigCache;
use Spiral\Twig\TwigEngine;
use Spiral\Views\Config\ViewsConfig;
use Spiral\Views\Processor\ContextProcessor;

final class TwigBootloader extends Bootloader implements DependedInterface
{
    const SINGLETONS = [
        TwigEngine::class => [self::class, 'twigEngine']
    ];

    /** @var ConfiguratorInterface */
    private $config;

    /**
     * @param ConfiguratorInterface $config
     */
    public function __construct(ConfiguratorInterface $config)
    {
        $this->config = $config;
    }

    /**
     * @param ContainerInterface $container
     * @param ViewsBootloader    $views
     */
    public function boot(ContainerInterface $container, ViewsBootloader $views)
    {
        $this->config->setDefaults('views/twig', [
            'options'    => [],
            'extensions' => [ContainerExtension::class],
            'processors' => [ContextProcessor::class]
        ]);

        $views->addEngine(TwigEngine::class);

        if ($container->has(LocaleProcessor::class)) {
            $this->addProcessor(LocaleProcessor::class);
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function setOption(string $key, $value)
    {
        $this->config->modify('views/twig', new Append('options', $key, $value));
    }

    /**
     * @param mixed $extension
     */
    public function addExtension($extension)
    {
        $this->config->modify('views/twig', new Append('extensions', null, $extension));
    }

    /**
     * @param mixed $processor
     */
    public function addProcessor($processor)
    {
        $this->config->modify('views/twig', new Append('processors', null, $processor));
    }

    /**
     * @return array
     */
    public function defineDependencies(): array
    {
        return [
            ViewsBootloader::class
        ];
    }

    /**
     * @param TwigConfig       $config
     * @param ViewsConfig      $viewConfig
     * @param FactoryInterface $factory
     * @return TwigEngine
     */
    protected function twigEngine(
        TwigConfig $config,
        ViewsConfig $viewConfig,
        FactoryInterface $factory
    ): TwigEngine {
        $engine = new TwigEngine(
            $viewConfig->cacheEnabled() ? new TwigCache($viewConfig->cacheDirectory()) : null
        );

        foreach ($config->getExtensions() as $extension) {
            if ($extension instanceof Autowire) {
                $extension = $extension->resolve($factory);
            }

            $engine->addExtension($extension);
        }

        foreach ($config->getProcessors() as $processor) {
            if ($processor instanceof Autowire) {
                $processor = $processor->resolve($factory);
            }

            $engine->addProcessor($processor);
        }

        return $engine;
    }
}