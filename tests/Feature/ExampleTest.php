<?php

declare(strict_types=1);

namespace Sylament\Schemas\Tests\Feature;

use Sylament\Schemas\Tests\Fixtures\DataFixtrues\AppFixtures;
use Sylament\Schemas\Tests\Fixtures\Entity\Person;
use Sylament\Schemas\Tests\Fixtures\Repository\PersonRepository;
use Sylament\Schemas\Tests\Fixtures\Twig\Components\ArticleForm;

it('can render component', function (): void {
    $articleForm = $this->createLiveComponent(ArticleForm::class);

    expect($articleForm->component()->count)->toBe(0);

    $articleForm->call('save');

    expect($articleForm->component()->count)->toBe(1);
});

it('can connect to database', function (): void {
    $this->executeFixture(AppFixtures::class);

    $repository = $this::getContainer()->get(PersonRepository::class);

    $person = new Person()
        ->setName('Martin');

    $this->entityManager->persist($person);

    $this->entityManager->flush();

    expect($repository->findAll())
        ->toHaveCount(2);
});
