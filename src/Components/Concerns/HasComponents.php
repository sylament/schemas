<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components\Concerns;

use Closure;
use Sylament\Schemas\Components\Component;
use Sylament\Support\Concerns\EvaluatesClosures;

trait HasComponents
{
    use EvaluatesClosures;

    /**
     * @var array<Component>|Closure(): array<Component>
     */
    protected array|Closure $components = [];

    /**
     * @param array<Component>|callable(): array<Component> $components
     */
    public function components(array|callable $components): static
    {
        $this->components = $this->evaluatable($components);

        return $this;
    }

    /**
     * @return array<Component>
     */
    public function getComponents(): array
    {
        $components = [];
        foreach ($this->evaluate($this->components) as $component) {
            $components[] = $component
                ->parentComponent($this)
                ->setInvoker($this->getInvoker())
                ->setPropertyAccessor($this->getPropertyAccessor());
        }

        return $components;
    }

    /**
     * @return array<Component>
     */
    public function getFlatComponents(): array
    {
        $components = $this->getComponents();
        foreach ($components as $component) {
            $components = [
                ...$components,
                ...$component->getFlatComponents(),
            ];
        }

        return $components;
    }
}
