<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components;

use Closure;

class Text extends Component
{
    protected ?string $color = null;

    protected string|Closure|null $template = '@SylamentSchemas/components/Text.html.twig';

    public static function make(?string $name = null): static
    {
        return parent::make()
            ->name($name)
            ->statePath($name);
    }

    public function color(?string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }
}
