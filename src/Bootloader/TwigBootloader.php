<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Bootloader;

use Psr\Container\ContainerInterface;
use Spiral\Config\ConfiguratorInterface;
use Spiral\Config\Patch\AppendPatch;
use Spiral\Core\Bootloader\Bootloader;
use Spiral\Core\FactoryInterface;
use Spiral\Twig\Config\TwigConfig;
use Spiral\Twig\Extension\ContainerExtension;
use Spiral\Twig\TwigCache;
use Spiral\Twig\TwigEngine;
use Spiral\Views\Config\ViewsConfig;
use Spiral\Views\Processor\ContextProcessor;

class TwigBootloader extends Bootloader
{
    const BOOT = true;

    const SINGLETONS = [
        TwigEngine::class => [self::class, 'twigEngine']
    ];

    /**
     * @param ConfiguratorInterface $configurator
     * @param ContainerInterface    $container
     */
    public function boot(ConfiguratorInterface $configurator, ContainerInterface $container)
    {
        $configurator->setDefaults('views/twig', [
            'options'    => [],
            'extensions' => [ContainerExtension::class],
            'processors' => [ContextProcessor::class]
        ]);

        $configurator->modify(
            'views',
            new AppendPatch('engines', null, TwigEngine::class)
        );

        if ($container->has('Spiral\Views\LocaleProcessor')) {
            $configurator->modify(
                'views/twig',
                new AppendPatch('processors', null, 'Spiral\Views\LocaleProcessor')
            );
        }
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
            $engine->addExtension($extension->resolve($factory));
        }

        foreach ($config->getProcessors() as $processor) {
            $engine->addProcessor($processor->resolve($factory));
        }

        return $engine;
    }
}