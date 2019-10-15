<?php

/**
 * Spiral Framework.
 *
 * @license   MIT
 * @author    Anton Titov (Wolfy-J)
 */
declare(strict_types=1);

namespace Spiral\Twig;

use Spiral\Views\ViewInterface;
use Twig\TemplateWrapper;

final class TwigView implements ViewInterface
{
    /** @var TemplateWrapper */
    private $wrapper;

    /**
     * @param TemplateWrapper $wrapper
     */
    public function __construct(TemplateWrapper $wrapper)
    {
        $this->wrapper = $wrapper;
    }

    /**
     * @inheritdoc
     */
    public function render(array $data = []): string
    {
        return $this->wrapper->render($data);
    }

    /**
     * @param string $name
     * @param array  $context
     *
     * @return string
     * @throws \Throwable
     */
    public function renderBlock(string $name, array $context = []): string
    {
        return $this->wrapper->renderBlock($name, $context);
    }
}
