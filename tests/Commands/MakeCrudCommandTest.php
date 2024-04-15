<?php

namespace Milwad\LaravelCrod\Tests\Commands;

use Milwad\LaravelCrod\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MakeCrudCommandTest extends TestCase
{
    /**
     * Test crud files created successfully.
     */
    #[Test]
    public function crud_files_created_successfully(): void
    {
        $this->artisan('crud:make', ['name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 5)
            ->assertSuccessful();


    }
}