<?php

declare(strict_types=1);

namespace Sylament\Schemas;

use Closure;
use Sylament\Schemas\Components\Component;
use Sylament\Schemas\Contracts\InteractsWithSchemas;

class Schema extends Component
{
    protected string|Closure|null $template = '@SylamentSchemas/components/Schema.html.twig';

    public function __construct(
        protected ?InteractsWithSchemas $liveComponent = null,
    ) {
        //
    }

    public static function make(?InteractsWithSchemas $liveComponent = null): static
    {
        return new static($liveComponent);
    }

    public function getLiveComponent(): ?InteractsWithSchemas
    {
        return $this->liveComponent;
    }

    /**
     * @param array<array-key, mixed>|null $data
     */
    public function fill(?array $data = null): static
    {
        $data ??= [];

        $this->rawState($data);

        foreach ($this->getComponents() as $component) {
            $component->fillStateWithNull();
        }

        return $this;
    }
}
