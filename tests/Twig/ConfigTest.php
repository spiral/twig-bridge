<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

use PHPUnit\Framework\TestCase;
use Spiral\Twig\Config\TwigConfig;

class ConfigTest extends TestCase
{
    public function testOptions()
    {
        $config = new TwigConfig([
            'options' => ['option' => 'value']
        ]);

        $this->assertSame(['option' => 'value'], $config->getOptions());
    }
}