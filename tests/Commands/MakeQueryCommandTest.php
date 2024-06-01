<?php

namespace Milwad\LaravelCrod\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Milwad\LaravelCrod\Tests\TestCase;
use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;
use PHPUnit\Framework\Attributes\Test;

class MakeQueryCommandTest extends TestCase
{
    use InteractsWithPublishedFiles;

    /**
     * Test all files are exists with correct data.
     */
    #[Test]
    public function all_files_are_exists_with_correct_data(): void
    {
        $this->artisan('crud:make', ['name' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        Artisan::call('migrate');

        $this->artisan('crud:query', ['table_name' => 'products', 'model' => 'Product'])
            ->expectsQuestion('Do you want something extra?', 0)
            ->assertSuccessful()
            ->expectsOutput('Crud files successfully generated...');

        $this->ensureCrudFilesExistsWithCorrectData();
    }

    /**
     * Ensure all crud files exists with correct data.
     */
    protected function ensureCrudFilesExistsWithCorrectData(): void
    {
        // Model
        $this->assertFileContains([
            '<?php',
            'namespace App\Models;',
            'class Product extends Model',
        ], 'app/Models/Product.php');

        // Controller
        $this->assertFileContains([
            '<?php',
            'namespace App\Http\Controllers;',
            'class ProductController extends Controller',
        ], 'app/Http/Controllers/ProductController.php');

        // Requests
        $this->assertFileContains([
            '<?php',
            'namespace App\Http\Requests;',
            'class ProductStoreRequest extends FormRequest',
            'public function authorize(): bool',
            'public function rules(): array',
        ], 'app/Http/Requests/ProductStoreRequest.php');
        $this->assertFileContains([
            '<?php',
            'namespace App\Http\Requests;',
            'class ProductUpdateRequest extends FormRequest',
            'public function authorize(): bool',
            'public function rules(): array',
        ], 'app/Http/Requests/ProductUpdateRequest.php');

        // Views
        $this->assertFileContains(['{{-- Start to write code - milwad-dev --}}'], 'resources/views/products/index.blade.php');
        $this->assertFileContains(['{{-- Start to write code - milwad-dev --}}'], 'resources/views/products/create.blade.php');
        $this->assertFileContains(['{{-- Start to write code - milwad-dev --}}'], 'resources/views/products/edit.blade.php');
    }
}