<?php

declare(strict_types=1);

namespace Spiral\Twig\Extension;

use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Provides access to container bindings using `get` alias.
 */
final class ContainerExtension extends AbstractExtension
{
    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    public function getFunctions(): array
    {
        return [new TwigFunction('get', [$this->container, 'get'])];
    }
}
