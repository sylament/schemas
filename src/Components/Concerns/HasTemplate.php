<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components\Concerns;

use Closure;

trait HasTemplate
{
    /**
     * @var string|Closure():string|null
     */
    protected string|Closure|null $template = null;

    /**
     * @param string|callable():string|null $template
     */
    public function template(string|callable|null $template): static
    {
        $this->template = $this->evaluatable($template);

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->evaluate($this->template);
    }
}
