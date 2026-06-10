<?php

declare(strict_types=1);

namespace Sylament\Schemas\Tests\Fixtures\Twig\Components;

use Sylament\Schemas\Concerns\HasSchemas;
use Sylament\Schemas\Contracts\InteractsWithSchemas;
use Sylament\Schemas\Schema;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
class ArticleForm implements InteractsWithSchemas
{
    use DefaultActionTrait;
    use HasSchemas;

    #[LiveProp]
    public int $count = 0;

    public function schema(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
            ]);
    }

    #[LiveAction]
    public function save(): void
    {
        $this->count++;
    }
}
