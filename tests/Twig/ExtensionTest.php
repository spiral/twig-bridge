<?php

declare(strict_types=1);

namespace Spiral\Twig\Tests\Twig;

use Spiral\Views\ViewContext;
use Twig\Extension\CoreExtension;

class ExtensionTest extends BaseTest
{
    protected $x;

    public function getX()
    {
        return $this->x;
    }

    public function testContainer(): void
    {
        $this->container->bind('test', $this);

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

    public function testGlobalExtension(): void
    {
        $twig = $this->getTwig();
        $env = $this->getTwig()->getEnvironment(new ViewContext());
        $extension = $env->getExtension(CoreExtension::class);
        $extension->setTimezone('Europe/Paris');

        $this->assertSame(
            '02:00 CEST',
            $twig->get('extensions:timezone', new ViewContext())
                ->render(['test_date' => new \DateTime('2021-06-01 00:00', new \DateTimeZone('UTC'))])
        );
    }

    public function testCustomExtension(): void
    {
        $twig = $this->getTwig();
        $env = $twig->getEnvironment(new ViewContext());
        $env->addExtension(new PrefixExtension());

        $this->assertSame(
            'hellotest_prefix',
            $twig->get('extensions:prefix', new ViewContext())->render(['test_word' => 'hello'])
        );
    }
}
