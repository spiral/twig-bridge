<?php

declare(strict_types=1);

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

use Spiral\Twig\Exception\SyntaxException;
use Spiral\Views\Context\ValueDependency;
use Spiral\Views\ViewContext;

class EngineTest extends BaseTest
{
    public function testList(): void
    {
        $views = $this->getTwig()->getLoader()->list();
        $this->assertContains('default:test', $views);
        $this->assertContains('other:test', $views);
    }

    public function testRender(): void
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

    public function testRenderInContext(): void
    {
        $ctx = new ViewContext();
        $ctx = $ctx->withDependency(new ValueDependency('name', 'Test'));

        $twig = $this->getTwig();
        $this->assertSame(
            'hello Anton of Test',
            $twig->get('other:ctx', $ctx)->render(['name' => 'Anton'])
        );
    }

    /**
     * @expectedException Twig\Error\RuntimeError
     */
    public function testRenderBlockException(): void
    {
        $ctx = new ViewContext();

        $twig = $this->getTwig();
        $twig->get('other:block', $ctx)->renderBlock('not_block');
    }

    public function testRenderBlock(): void
    {
        $ctx = new ViewContext();
        $message = 'hello test';
        $twig = $this->getTwig();

        $this->assertSame(
            $message,
            $twig->get('other:block', $ctx)->renderBlock('test_block', ['message' => $message])
        );
    }

    public function testSyntaxException(): void
    {
        $twig = $this->getTwig();
        try {
            $twig->compile('other:error', new ViewContext());
        } catch (SyntaxException $e) {
            $this->assertContains('end of template', $e->getMessage());
            $this->assertContains('error.twig', $e->getFile());
            $this->assertSame(2, $e->getLine());
        }
    }
}
