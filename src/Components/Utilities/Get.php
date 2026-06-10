<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components\Utilities;

use Sylament\Schemas\Contracts\InteractsWithSchemas;
use Sylament\Schemas\Components\Component;
use Sylament\Support\Services\PropertyAccessor;

class Get
{
    public function __construct(
        protected Component $component,
    ) {
        //
    }

    public function __invoke(?string $path = null, bool $isAbsolute = false): mixed
    {
        $liveComponent = $this->component->getLiveComponent();
        if (!$liveComponent instanceof InteractsWithSchemas) {
            return null;
        }

        $path = $this->component->resolveRelativeStatePath($path, $isAbsolute);

        return $this->getPropertyAccessor()
            ->getValue($liveComponent, $path);
    }

    private function getPropertyAccessor(): PropertyAccessor
    {
        return $this->component->getPropertyAccessor();
    }
}
