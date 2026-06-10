<?php

declare(strict_types=1);

namespace Sylament\Schemas\Components\Utilities;

use RuntimeException;
use Sylament\Schemas\Contracts\InteractsWithSchemas;
use Sylament\Schemas\Components\Component;
use Sylament\Support\Services\PropertyAccessor;

class Set
{
    public function __construct(
        protected Component $component,
    ) {
        //
    }

    public function __invoke(?string $path = null, mixed $state = null, bool $isAbsolute = false): mixed
    {
        $liveComponent = $this->component->getLiveComponent();
        if (!$liveComponent instanceof InteractsWithSchemas) {
            return null;
        }

        $path = $this->component->resolveRelativeStatePath($path, $isAbsolute);
        if ($path === null) {
            throw new RuntimeException('Unable to resolve path.');
        }

        $this->getPropertyAccessor()
            ->setValue($liveComponent, $path, $state);

        return $state;
    }

    private function getPropertyAccessor(): PropertyAccessor
    {
        return $this->component->getPropertyAccessor();
    }
}
