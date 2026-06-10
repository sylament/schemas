<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components\Concerns;

use Closure;

trait HasLabel
{
    /**
     * @var string|Closure(): string|null
     */
    protected string|Closure|null $label = null;

    /**
     * @var bool|Closure(): bool
     */
    protected bool|Closure $isLabelHidden = false;

    /**
     * @param string|callable():string|null $label
     */
    public function label(string|callable|null $label): static
    {
        $this->label = $this->evaluatable($label);

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->evaluate($this->label);
    }

    /**
     * @param bool|callable(): bool $isLabelHidden
     */
    public function hiddenLabel(bool|callable $isLabelHidden = true): static
    {
        $this->isLabelHidden = $this->evaluatable($isLabelHidden);

        return $this;
    }

    public function isLabelHidden(): bool
    {
        return $this->evaluate($this->isLabelHidden);
    }
}
