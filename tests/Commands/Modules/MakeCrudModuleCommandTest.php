<?php

namespace Milwad\LaravelCrod\Tests\Commands\Modules;

use Milwad\LaravelCrod\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MakeCrudModuleCommandTest extends TestCase
{
    /**
     * Test crud files created successfully.
     */
    #[Test]
    public function crud_files_created_successfully(): void
    {
        $this->artisan('crud:make', ['name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        $this->ensureCrudFileCreated();
    }

    /**
     * Ensure the crud files successfully created.
     */
    protected function ensureCrudFileCreated(string $modelFolder = 'Entities'): void
    {
        // Model
        $this->assertFileExists(base_path("Modules/Product/$modelFolder/Product.php"));

        // Migration
        $this->migrationExists('create_products_table');

        // Controller
        $this->assertFileExists(base_path('Modules/Product/Http/Controllers/ProductController.php'));

        // Requests
        $this->assertFileExists(base_path('Modules/Product/Http/Requests/ProductStoreRequest.php'));
        $this->assertFileExists(base_path('Modules/Product/Http/Requests/ProductUpdateRequest.php'));

        // View
        $this->assertFileExists(base_path('Modules/Product/Resources/Views/index.blade.php'));
        $this->assertFileExists(base_path('Modules/Product/Resources/Views/create.blade.php'));
        $this->assertFileExists(base_path('Modules/Product/Resources/Views/edit.blade.php'));
    }

    /**
     * Check migration file exists.
     */
    protected function migrationExists(string $mgr): bool
    {
        $path = base_path('Modules/Product/Database/Migrations/');
        $files = scandir($path);

        foreach ($files as &$value) {
            $pos = strpos($value, $mgr);
            if ($pos !== false) {
                return true;
            }
        }

        return false;
    }
}