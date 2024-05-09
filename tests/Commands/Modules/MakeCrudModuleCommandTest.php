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
        $this->artisan('crud:make-module', ['module_name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        $this->ensureCrudFileCreated();
    }

    /**
     * Test crud files created successfully with seeder.
     */
    #[Test]
    public function crud_files_created_successfully_with_seeder(): void
    {
        $this->artisan('crud:make-module', ['module_name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 1)
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        $this->ensureCrudFileCreated();

        // Ensure seeder exists
        $this->assertFileExists(base_path('Modules/Product/Database/Seeders/ProductSeeder.php'));
    }

    /**
     * Test crud files created successfully with factory.
     */
    #[Test]
    public function crud_files_created_successfully_with_factory(): void
    {
        $this->artisan('crud:make-module', ['module_name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 2)
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        $this->ensureCrudFileCreated();

        // Ensure factory exists
        $this->assertFileExists(base_path('Modules/Product/Database/Factories/ProductFactory.php'));
    }

    /**
     * Test crud files created successfully with repository.
     */
    #[Test]
    public function crud_files_created_successfully_with_repository(): void
    {
        $this->artisan('crud:make-module', ['module_name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 3)
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        $this->ensureCrudFileCreated();

        // Ensure repository exists
        $this->assertFileExists(base_path('Modules/Product/Repositories/ProductRepo.php'));
    }

    /**
     * Test crud files created successfully with service.
     */
    #[Test]
    public function crud_files_created_successfully_with_service(): void
    {
        $this->artisan('crud:make-module', ['module_name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 4)
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        $this->ensureCrudFileCreated();

        // Ensure service exists
        $this->assertFileExists(base_path('Modules/Product/Services/ProductService.php'));
    }

    /**
     * Test crud files created successfully with tests.
     */
    #[Test]
    public function crud_files_created_successfully_with_tests(): void
    {
        $this->artisan('crud:make-module', ['module_name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 5)
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        $this->ensureCrudFileCreated();

        // Ensure tests exists
        $this->assertFileExists(base_path('Modules/Product/Tests/Feature/ProductTest.php'));
        $this->assertFileExists(base_path('Modules/Product/Tests/Unit/ProductTest.php'));
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