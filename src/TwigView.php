<?php

declare(strict_types=1);

namespace Spiral\Twig;

use Spiral\Views\ViewInterface;
use Twig\TemplateWrapper;

final class TwigView implements ViewInterface
{
    public function __construct(
        private readonly TemplateWrapper $wrapper
    ) {
    }

    public function render(array $data = []): string
    {
        return $this->wrapper->render($data);
    }

    /**
     * @throws \Throwable
     */
    public function renderBlock(string $name, array $context = []): string
    {
        return $this->wrapper->renderBlock($name, $context);
    }
}
