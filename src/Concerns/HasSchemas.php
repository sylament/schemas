<?php

declare(strict_types=1);

namespace Sylament\Schemas\Concerns;

use RuntimeException;
use Sylament\Schemas\Schema;
use Sylament\Support\Concerns\AccessesProperties;
use Sylament\Support\Concerns\EvaluatesClosures;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\Attribute\PostHydrate;
use Symfony\UX\LiveComponent\Attribute\PreDehydrate;

trait HasSchemas
{
    use AccessesProperties;
    use EvaluatesClosures;

    #[LiveProp]
    public array $oldState = [];

    /**
     * @var array<string, Schema>
     */
    protected array $cachedSchemas = [];

    #[PreDehydrate]
    public function persistOldState(): void
    {
        foreach ($this->getCachedSchemas() as $schema) {
            foreach ($schema->getFlatComponents() as $component) {
                if (!$component->hasStatePath()) {
                    continue;
                }

                $this->setValue($this->oldState, $component->getStatePath(), $component->getRawState());
            }
        }
    }

    #[PostHydrate]
    public function afterStateUpdated(): void
    {
        foreach ($this->getCachedSchemas() as $schema) {
            foreach ($schema->getFlatComponents() as $component) {
                if (!$component->hasStatePath()) {
                    continue;
                }

                $oldState = $this->getValue($this->oldState, $component->getStatePath());
                if ($oldState === $component->getRawState()) {
                    continue;
                }

                $component->evaluate($component->getAfterStateUpdated(), [
                    'oldState' => $oldState,
                ]);
            }
        }
    }

    /**
     * @return array<string>
     */
    public function schemas(): array
    {
        return [
            'schema',
        ];
    }

    public function getSchema(string $name = 'schema'): Schema
    {
        $cachedSchema = $this->cachedSchemas[$name] ?? null;
        if ($cachedSchema instanceof Schema) {
            return $cachedSchema;
        }

        $schema = $this->$name($this->makeSchema());
        if (!$schema instanceof Schema) {
            throw new RuntimeException('Schema must be an instance of Schema');
        }

        $this->cachedSchemas[$name] = $schema;

        return $schema;
    }

    /**
     * @return array<string, Schema>
     */
    public function getCachedSchemas(): array
    {
        foreach ($this->schemas() as $name) {
            if (!array_key_exists((string) $name, $this->cachedSchemas)) {
                $this->getSchema($name);
            }
        }

        return $this->cachedSchemas;
    }

    public function makeSchema(): Schema
    {
        return Schema::make($this)
            ->setInvoker($this->getInvoker())
            ->setPropertyAccessor($this->getPropertyAccessor());
    }
}
