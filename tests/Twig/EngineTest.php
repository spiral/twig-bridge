<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

use Spiral\Twig\Exception\SyntaxException;
use Spiral\Views\ViewContext;

class EngineTest extends BaseTest
{
    public function testList()
    {
        $views = $this->getTwig()->getLoader()->list();
        $this->assertContains('default:test', $views);
        $this->assertContains('other:test', $views);
    }

    public function testRender()
    {
        $twig = $this->getTwig();
        $this->assertSame(
            'test',
            $twig->get('test', new ViewContext())->render([])
        );

        $this->assertSame(
            'other test',
            $twig->get('other:test', new ViewContext())->render([])
        );
    }

    public function testSyntaxException()
    {
        $twig = $this->getTwig();
        try {
            $twig->compile('other:error', new ViewContext());
        } catch (SyntaxException $e) {
            $this->assertContains("end of template", $e->getMessage());
            $this->assertContains("error.twig", $e->getFile());
            $this->assertSame(2, $e->getLine());
        }
    }
}