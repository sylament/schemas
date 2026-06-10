<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components\Concerns;

use Closure;

trait HasStateBindingModifiers
{
    /**
     * @var bool|Closure():bool
     */
    protected bool|Closure $isLive = false;

    protected int|null $liveDebounce = null;

    protected bool $isLiveOnBlur = false;

    /**
     * @param bool|callable():bool $condition
     */
    public function live(
        bool $onBlur = false,
        int|null $debounce = null,
        bool|callable $condition = true,
    ): static {
        $this->isLive = $this->evaluatable($condition);
        $this->isLiveOnBlur = $onBlur;
        $this->liveDebounce = $debounce;

        return $this;
    }

    public function reactive(): static
    {
        $this->live();
        return $this;
    }

    public function lazy(): static
    {
        $this->live(onBlur: true);

        return $this;
    }

    public function debounce(int|null $delay = 500): static
    {
        $this->live(debounce: $delay);

        return $this;
    }

    public function getDataModel(): ?string
    {
        $isLive = $this->evaluate($this->isLive);
        if (!$isLive) {
            return "norender|{$this->getStatePath()}";
        }

        if ($this->isLiveOnBlur) {
            return "on(change)|{$this->getStatePath()}";
        }

        if ($this->liveDebounce) {
            return "debounce({$this->liveDebounce})|{$this->getStatePath()}";
        }

        return $this->getStatePath();
    }
}
