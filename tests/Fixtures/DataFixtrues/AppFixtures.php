<?php

declare(strict_types=1);

namespace Sylament\Schemas\Tests\Fixtures\DataFixtrues;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Sylament\Schemas\Tests\Fixtures\Entity\Person;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $person = new Person()
            ->setName('John Doe');

        $manager->persist($person);

        $manager->flush();
    }
}
