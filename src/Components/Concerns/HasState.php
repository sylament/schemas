<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components\Concerns;

use Closure;
use RuntimeException;
use Sylament\Schemas\Components\Utilities\Get;
use Sylament\Schemas\Components\Utilities\Set;
use Sylament\Support\Concerns\AccessesProperties;
use Symfony\Component\String\UnicodeString;

trait HasState
{
    use AccessesProperties;

    protected ?string $statePath = null;

    protected ?string $cachedAbsoluteStatePath = null;

    protected ?Closure $afterStateUpdated = null;

    public function statePath(?string $statePath): static
    {
        $this->statePath = $statePath;

        return $this;
    }

    public function hasStatePath(): bool
    {
        return $this->statePath !== null;
    }

    public function getStatePath(bool $isAbsolute = true): ?string
    {
        if (!$isAbsolute) {
            return $this->statePath;
        }

        if ($this->cachedAbsoluteStatePath !== null) {
            return $this->cachedAbsoluteStatePath;
        }

        $pathComponents = [];
        if ($parentComponentStatePath = $this->getParentComponent()?->getStatePath()) {
            $pathComponents[] = $parentComponentStatePath;
        }

        if ($this->hasStatePath()) {
            $pathComponents[] = $this->statePath;
        }

        return $this->cachedAbsoluteStatePath = implode('.', $pathComponents);
    }

    public function resolveRelativeStatePath(?string $path = null, bool $isAbsolute = false): ?string
    {
        if (str_starts_with((string)$path, '/')) {
            $isAbsolute = true;
            $path = (string)new UnicodeString((string)$path)->after('/');
        }

        if ($isAbsolute) {
            return $path;
        }

        $containerPath = $this->getParentComponent()?->getStatePath();
        while (str_starts_with((string)$path, '../')) {
            $containerPath = str_contains((string) $containerPath, '.') ?
                (string)new UnicodeString((string)$containerPath)->beforeLast('.') :
                null;

            $path = (string)new UnicodeString((string)$path)->after('../');
        }

        if (new UnicodeString((string)$containerPath)->isEmpty()) {
            return $path;
        }

        return new UnicodeString(mb_ltrim((string)$path, './'))->isEmpty()
            ? $containerPath
            : "{$containerPath}.{$path}";
    }

    public function getState(): mixed
    {
        return $this->getRawState();
    }

    public function getRawState(): mixed
    {
        if (!$this->hasStatePath()) {
            return null;
        }

        $liveComponent = $this->getLiveComponent();
        if ($liveComponent === null) {
            throw new RuntimeException('Live component not found when trying to get state');
        }

        return $this->getValue($liveComponent, $this->getStatePath());
    }

    public function state(mixed $state): static
    {
        return $this->rawState($state);
    }

    public function rawState(mixed $state): static
    {
        $statePath = $this->getStatePath();
        if ($statePath === null) {
            throw new RuntimeException('Can not set state without state path');
        }

        $liveComponent = $this->getLiveComponent();
        if ($liveComponent === null) {
            throw new RuntimeException('Live component not found when trying to set state');
        }

        $this->setValue($liveComponent, $statePath, $state);

        return $this;
    }

    public function fillStateWithNull(): static
    {
        if ($this->getRawState() === null) {
            $this->state(null);
        }

        foreach ($this->getComponents() as $component) {
            $component->fillStateWithNull();
        }

        return $this;
    }

    /**
     * @param callable(): void|null $callback
     */
    public function afterStateUpdated(?callable $callback): static
    {
        $this->afterStateUpdated = $this->evaluatable($callback);

        return $this;
    }

    public function getAfterStateUpdated(): ?Closure
    {
        return $this->afterStateUpdated;
    }

    public function makeGetUtility(): Get
    {
        return new Get($this);
    }

    public function makeSetUtility(): Set
    {
        return new Set($this);
    }
}
