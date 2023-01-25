<?php

namespace Milwad\LaravelCrod\Traits;

use Illuminate\Support\Arr;
use Milwad\LaravelCrod\Datas\QueryData;

trait QueryTrait
{
    /**
     * Add db column to string.
     *
     * @param array $itemsDB
     * @return string
     * @throws \Exception
     */
    private function addDBCulumnsToString(array $itemsDB)
    {
        $columns = '';
        $excepts = config('laravel-crod.queries.except_columns_in_fillable');

        if (! is_array($excepts)) {
            throw new \RuntimeException("Except columns is not an array");
        }

        foreach ($excepts as $except) {
            if (Arr::exists($itemsDB, $except)) {
                Arr::forget($itemsDB, $except);
            }
        }
        foreach ($itemsDB as $db) {
            $columns .= "'$db', ";
        }

        return $columns;
    }

    /**
     * Add data to model.
     *
     * @param mixed $items
     * @param mixed $filename
     * @return void
     */
    private function addDataToModel(mixed $items, string $filename)
    {
        [$line_i_am_looking_for, $lines] = $this->lookingLinesWithIgnoreLines($filename, 10);
        $lines[$line_i_am_looking_for] = QueryData::getModelData($items);

        file_put_contents($filename, implode("\n", $lines));
    }

    /**
     * Add data to controller.
     *
     * @param string $model
     * @param string $filename
     * @return void
     */
    private function addDataToController(string $model, string $filename)
    {
        [$line_i_am_looking_for, $lines] = $this->lookingLinesWithIgnoreLines($filename);
        $lines[$line_i_am_looking_for] = $this->option('id-controller')
            ? $this->controllerId()
            : $this->controllerRouteModelBinding($model);

        file_put_contents($filename, implode("\n", $lines));
    }

    /**
     * Add data to service.
     *
     * @param string $model
     * @param string $filename
     * @return void
     */
    private function addDataToService(string $model, string $filename)
    {
        [$line_i_am_looking_for, $lines] = $this->lookingLinesWithIgnoreLines($filename, 6);
        $lines[$line_i_am_looking_for] = QueryData::getServiceData($model, '$request', '$id');

        file_put_contents($filename, implode("\n", $lines));
        $this->addUseToService($model, $filename);
    }

    /**
     * Add use to Service for module.
     *
     * @param string $model
     * @param string $filename
     * @return void
     */
    private function addUseToService(string $model, string $filename)
    {
        [$line_i_am_looking_for, $lines] = $this->lookingLinesWithIgnoreLines($filename, 3);
        $lines[$line_i_am_looking_for] = "
use $this->module_name_space\\$model\Entities\\$model;
";
        file_put_contents($filename, implode("\n", $lines));
    }

    /**
     * Add data to repository.
     *
     * @param string $model
     * @param string $filename
     * @return void
     */
    private function addDataToRepo(string $model, string $filename)
    {
        [$line_i_am_looking_for, $lines] = $this->lookingLinesWithIgnoreLines($filename, 6);
        $lines[$line_i_am_looking_for] = QueryData::getRepoData($model, '$id');

        file_put_contents($filename, implode("\n", $lines));
        $this->addUseToRepo($model, $filename);
    }

    /**
     * Add use to repository.
     *
     * @param string $model
     * @param string $filename
     * @return void
     */
    private function addUseToRepo(string $model, string $filename)
    {
        [$line_i_am_looking_for, $lines] = $this->lookingLinesWithIgnoreLines($filename, 3);
        $lines[$line_i_am_looking_for] = QueryData::getUseRepoData($model);

        file_put_contents($filename, implode("\n", $lines));
    }

    /**
     * @param string $filename
     * @param int $looking_for
     * @return array
     */
    private function lookingLinesWithIgnoreLines(string $filename, int $looking_for = 8): array
    {
        return [
            $looking_for,
            file($filename, FILE_IGNORE_NEW_LINES)
        ];
    }
}