<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components;

use Closure;

class Group extends Component
{
    protected string|Closure|null $template = '@SylamentSchemas/components/Group.html.twig';

    public static function make(): static
    {
        return new static();
    }
}
