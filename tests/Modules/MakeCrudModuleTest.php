<?php

namespace Milwad\LaravelCrod\Tests\Modules;

use Illuminate\Support\Facades\File;
use Milwad\LaravelCrod\Tests\BaseTest;

class MakeCrudModuleTest extends BaseTest
{
    private string $name = 'Product';

    private string $command = 'crud:make-module';

    private ?string $module;

    private string $question = 'Do you want something extra?';

    protected function setUp(): void
    {
        parent::setUp();

        $this->module = config('laravel-crod.modules.module_namespace', 'Modules');
    }

    /**
     * Test check all files create when user run command 'crud:make'.
     *
     * @return void
     */
    public function test_check_to_create_files_with_command_crud_make()
    {
        $this->withoutExceptionHandling();
        $this->artisan($this->command, [
            'module_name' => $this->name,
        ])->expectsQuestion('Do you want something extra?', 5);

        $this->checkAllToModelIsCreatedWithOriginalName();
        $this->checkAllToMigrationIsCreatedWithOriginalName();
        $this->checkAllToControllerIsCreatedWithOriginalName();
        $this->checkAllToRequestIsCreatedWithOriginalName();
//        $this->checkAllToViewIsCreatedWithOriginalName();
    }

    /**
     * Test check all files create for module when user run command 'crud:make' with options.
     *
     * @return void
     */
    public function test_check_to_create_files_for_module_with_command_crud_make_with_options()
    {
        $this->withoutExceptionHandling();
        $this->artisan($this->command, ['module_name' => $this->name])
            ->expectsQuestion($this->question, 0)
            ->expectsQuestion($this->question, 1)
            ->expectsQuestion($this->question, 2)
            ->expectsQuestion($this->question, 3)
            ->expectsQuestion($this->question, 4)
            ->expectsQuestion($this->question, 5)
            ->assertExitCode(0);

        $this->checkAllToModelIsCreatedWithOriginalName();
        $this->checkAllToMigrationIsCreatedWithOriginalName();
        $this->checkAllToControllerIsCreatedWithOriginalName();
        $this->checkAllToRequestIsCreatedWithOriginalName();
        $this->checkAllToViewIsCreatedWithOriginalName();
        $this->checkAllToServiceIsCreatedWithOriginalName();
        $this->checkAllToRepositoryIsCreatedWithOriginalName();
        $this->checkAllToTestsIsCreatedWithOriginalName();
    }

    private function checkAllToModelIsCreatedWithOriginalName(): void
    {
        $modelFolderName = config('laravel-crod.modules.model_path', 'Entities');
        $filename = base_path("$this->module\\$this->name\\$modelFolderName\\$this->name.php");

        $this->assertEquals(1, file_exists($filename));
        $this->assertEquals($this->name, basename($filename, '.php'));
    }

    private function checkAllToMigrationIsCreatedWithOriginalName(): void
    {
//        $name = strtolower($this->name);
        // TODO
//        $file = !str_ends_with($name, 'y')
//            ? $this->migrationExists("create_{$name}ies_table")
//            : $this->migrationExists("create_{$name}s_table");
        //dd(now());
//        $this->assertEquals(1, $file);
    }

    /**
     * Check migration file exists.
     *
     *
     * @return bool
     */
    private function migrationExists(string $mgr)
    {
        $modelFolderName = config('laravel-crod.modules.migration_path', 'Database\Migrations');
        $path = base_path("$this->module\\$this->name\\$modelFolderName");
        $files = scandir($path);

        foreach ($files as &$value) {
            $pos = strpos($value, $mgr);
            if ($pos !== false) {
                return true;
            }
        }

        return false;
    }

    private function checkAllToControllerIsCreatedWithOriginalName(): void
    {
        $modelFolderName = config('laravel-crod.modules.controller_path', 'Http\Controllers');
        $name = $this->name.'Controller';
        $filename = base_path("$this->module\\$this->name\\$modelFolderName\\$name.php");

        $this->assertEquals(1, file_exists($filename));
        $this->assertEquals($name, basename($filename, '.php'));
    }

    private function checkAllToRequestIsCreatedWithOriginalName(): void
    {
        $modelFolderName = config('laravel-crod.modules.request_path', 'Http\Requests');
        $filename = base_path("$this->module\\$this->name\\$modelFolderName\\");

        $this->assertEquals(1, file_exists($filename . "ProductStoreRequest.php"));
        $this->assertEquals(1, file_exists($filename . "ProductUpdateRequest.php"));
    }

    private function checkAllToViewIsCreatedWithOriginalName(): void
    {
        $lowerName = strtolower($this->name);
        $latest = str_ends_with($lowerName, 'y') ? 'ies' : 's';
        $view = $lowerName.$latest.'.blade';
        $viewFolderName = config('laravel-crod.modules.view_path', 'Resources\Views');
        $filename = base_path("$this->module\\$this->name\\$viewFolderName\\$view.php");

        $this->assertEquals(1, file_exists($filename));
        $this->assertEquals($view, basename($filename, '.php'));
    }

    private function checkAllToServiceIsCreatedWithOriginalName(): void
    {
        $service = ucfirst($this->name).'Service';
        $serviceFolderName = config('laravel-crod.modules.service_path', 'Services');
        $filename = base_path("$this->module\\$this->name\\$serviceFolderName\\$service.php");

        $this->assertEquals(1, file_exists($filename));
        $this->assertEquals($service, basename($filename, '.php'));
    }

    private function checkAllToRepositoryIsCreatedWithOriginalName(): void
    {
        $repo = ucfirst($this->name).config('laravel-crod.repository_namespace', 'Repo');
        $repoFolderName = config('laravel-crod.modules.repository_path', 'Repositories');
        $filename = base_path("$this->module\\$this->name\\$repoFolderName\\$repo.php");

        $this->assertEquals(1, file_exists($filename));
        $this->assertEquals($repo, basename($filename, '.php'));
    }

    private function checkAllToTestsIsCreatedWithOriginalName(): void
    {
        // Feature test
        $test = ucfirst($this->name).'Test';
        $repoFolderName = config('laravel-crod.modules.feature_test_path', 'Tests\Feature');
        $filename = base_path("$this->module\\$this->name\\$repoFolderName\\$test.php");

        $this->assertEquals(1, file_exists($filename));
        $this->assertEquals($test, basename($filename, '.php'));

        // Unit test
        $test = ucfirst($this->name).'Test';
        $repoFolderName = config('laravel-crod.modules.unit_test_path', 'Tests\Unit');
        $filename = base_path("$this->module\\$this->name\\$repoFolderName\\$test.php");

        $this->assertEquals(1, file_exists($filename));
        $this->assertEquals($test, basename($filename, '.php'));
    }
}
