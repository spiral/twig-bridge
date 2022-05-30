<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

declare(strict_types=1);

namespace Spiral\Twig\Config;

use Spiral\Core\Container\Autowire;
use Spiral\Core\InjectableConfig;
use Spiral\Twig\Exception\ConfigException;

final class TwigConfig extends InjectableConfig
{
    public const CONFIG = 'views/twig';

    protected array $config = [
        'options'    => [],
        'extensions' => [],
        'processors' => []
    ];

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->config['options'];
    }

    /**
     * @return Autowire[]
     */
    public function getExtensions(): array
    {
        $extensions = [];
        foreach ($this->config['extensions'] as $extension) {
            if (is_object($extension) && !$extension instanceof Autowire) {
                $extensions[] = $extension;
                continue;
            }

            $extensions[] = $this->wire($extension);
        }

        return $extensions;
    }

    /**
     * @return Autowire[]
     */
    public function getProcessors(): array
    {
        $processors = [];
        foreach ($this->config['processors'] as $processor) {
            if (is_object($processor) && !$processor instanceof Autowire) {
                $processors[] = $processor;
                continue;
            }

            $processors[] = $this->wire($processor);
        }

        return $processors;
    }

    /**
     * @param mixed $item
     * @return Autowire
     *
     * @throws ConfigException
     */
    private function wire($item): Autowire
    {
        if ($item instanceof Autowire) {
            return $item;
        }

        if (is_string($item)) {
            return new Autowire($item);
        }

        throw new ConfigException('Invalid class reference in view config.');
    }
}
