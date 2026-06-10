<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components;

use Closure;

class Grid extends Component
{
    protected string|Closure|null $template = '@SylamentSchemas/components/Grid.html.twig';

    public function __construct(
        protected int $columns,
    ) {
        //
    }

    public static function make(int $columns = 2): static
    {
        return new static($columns);
    }

    public function columns(int $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    public function getColumns(): int
    {
        return $this->columns;
    }
}
