<?php

namespace Milwad\LaravelCrod\Tests\Commands;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Milwad\LaravelCrod\Commands\MakeCrudCommand;
use Milwad\LaravelCrod\Tests\TestCase;
use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;
use PHPUnit\Framework\Attributes\Test;

class MakeQueryCommandTest extends TestCase
{
    use InteractsWithPublishedFiles;

    /**
     * Test add query to crud files with model binding.
     */
    #[Test]
    public function add_query_to_crud_files_with_model_binding(): void
    {
        $this->artisan(MakeCrudCommand::class, ['name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        $this->artisan('migrate');

        $this->artisan('crud:query', [
            'table_name' => 'products',
            'model' => 'Product'
        ])->assertSuccessful()->expectsOutput('Query added successfully');

        // Check file contains
        $this->assertFileContains([
            'namespace App\Models;',
            'class Product extends Model',
            'protected $fillable',
        ], app_path('Models/Product.php'));
    }
}