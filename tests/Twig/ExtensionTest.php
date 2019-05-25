<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

use Spiral\Views\ViewContext;

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
    //TODO can also be set globally by calling setTimezone():
//    public function testTimeZoneExtension()
//    {
//        $this->getTwig()->get('extensions:timezone',new ViewContext())->render(['test_date' => '00:00']);
//
//    }

}