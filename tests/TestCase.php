<?php

declare(strict_types=1);

namespace Sylament\Schemas\Tests;

use Sylament\Support\Tests\TestCase as SupportTestCase;

abstract class TestCase extends SupportTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->tearDownDatabase();
    }
}
