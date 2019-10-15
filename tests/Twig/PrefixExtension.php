<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Twig\Tests\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class PrefixExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('test_prefix', [$this, 'addPrefix']),
        ];
    }

    public function addPrefix($value)
    {
        return $value . 'test_prefix';
    }
}
