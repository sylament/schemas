<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components;

use Sylament\Schemas\Components\Concerns\BelongsToParentComponent;
use Sylament\Schemas\Components\Concerns\HasComponents;
use Sylament\Schemas\Components\Concerns\HasName;
use Sylament\Schemas\Components\Concerns\HasState;
use Sylament\Schemas\Components\Concerns\HasStateBindingModifiers;
use Sylament\Schemas\Components\Concerns\HasTemplate;
use Sylament\Support\Data\ClosureParameter;

abstract class Component
{
    use BelongsToParentComponent;
    use HasComponents;
    use HasName;
    use HasState;
    use HasStateBindingModifiers;
    use HasTemplate;

    public static function make(): static
    {
        return new static();
    }

    protected function resolveDefaultClosureDependencyForEvaluationByName(string $parameterName): ?ClosureParameter
    {
        return match ($parameterName) {
            'component' => ClosureParameter::make($this),
            'liveComponent' => ClosureParameter::make($this->getLiveComponent()),
            'get' => ClosureParameter::make($this->makeGetUtility()),
            'set' => ClosureParameter::make($this->makeSetUtility()),
            'state' => ClosureParameter::make($this->getState()),
            'rawState' => ClosureParameter::make($this->getRawState()),
            default => null,
        };
    }
}
