<?php

declare(strict_types=1);

namespace Spiral\Twig\Exception;

use Spiral\Views\Exception\CompileException;
use Twig\Error\SyntaxError;

class SyntaxException extends CompileException
{
    public static function fromTwig(SyntaxError $error): SyntaxException
    {
        $exception = new self($error);
        $exception->file = $error->getSourceContext()->getPath();
        $exception->line = $error->getTemplateLine();

        return $exception;
    }
}
