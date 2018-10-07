<?php
/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */

namespace Spiral\Twig\Tests\Twig;

class EngineTest extends BaseTest
{
    public function testList()
    {
        $views = $this->getTwig()->getLoader()->list();
        $this->assertContains('default:test', $views);
    }
}