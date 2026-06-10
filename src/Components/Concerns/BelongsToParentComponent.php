<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components\Concerns;

use Sylament\Schemas\Components\Component;
use Sylament\Schemas\Contracts\InteractsWithSchemas;
use Sylament\Schemas\Schema;

trait BelongsToParentComponent
{
    protected ?Component $parentComponent = null;

    public function parentComponent(Component $component): static
    {
        $this->parentComponent = $component;

        return $this;
    }

    public function getParentComponent(): ?Component
    {
        return $this->parentComponent;
    }

    public function getSchema(): ?Schema
    {
        if ($this instanceof Schema) {
            return $this;
        }

        return $this->getParentComponent()?->getSchema();
    }

    public function getLiveComponent(): ?InteractsWithSchemas
    {
        return $this->getSchema()?->getLiveComponent();
    }

    public function isRoot(): bool
    {
        return $this->parentComponent === null;
    }
}
