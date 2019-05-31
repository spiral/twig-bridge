<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

use Spiral\Views\ViewContext;
use Twig\Extension\AbstractExtension;
use Twig\Extension\CoreExtension;
use Twig\TwigFilter;

class ExtensionTest extends BaseTest
{
    protected $x;

    public function getX()
    {
        return $this->x;
    }

    public function testContainer()
    {
        $this->container->bind("test", $this);

        $this->x = 'XXX';
        $this->assertSame(
            'Get from: XXX',
            $this->getTwig()->get('get', new ViewContext())->render([])
        );

        $this->x = 'YYY';
        $this->assertSame(
            'Get from: YYY',
            $this->getTwig()->get('get', new ViewContext())->render([])
        );
    }

    public function testGlobalExtension()
    {
        $twig = $this->getTwig();
        $env = $this->getTwig()->getEnvironment(new ViewContext());
        $extension = $env->getExtension(CoreExtension::class);
        $extension->setTimezone('Europe/Paris');

        $this->assertSame('02:00 CEST',
            $twig->get('extensions:timezone', new ViewContext())
                ->render(['test_date' => new \DateTime('00:00')])
        );
    }

    public function testCustomExtension()
    {
        $twig = $this->getTwig();
        $env = $twig->getEnvironment(new ViewContext());
        $env->addExtension(new PrefixExtension());

        $this->assertSame('hellotest_prefix',
            $twig->get('extensions:prefix', new ViewContext())->render(['test_word' => 'hello'])
        );
    }
}

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