<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

use Spiral\Views\ViewContext;

class EngineTest extends BaseTest
{
    public function testList()
    {
        $views = $this->getTwig()->getLoader()->list();
        $this->assertContains('default:test', $views);
    }

    public function testRender()
    {
        $twig = $this->getTwig();
        $this->assertSame('test', $twig->get('test', new ViewContext())->render([]));
    }
}