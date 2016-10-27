<?php
namespace Thedevsaddam\LaravelSchema\Schema;
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 9/17/16
 * Time: 11:12 AM
 */
trait Helper
{

    /**
     * @param $namespaceModel
     * @return mixed
     * @throws \Exception
     */
    public function tableNameFromModel($namespaceModel)
    {
        $modelPath = app()->getNamespace() . $namespaceModel;
        $modelPath = class_exists($modelPath) ? $modelPath : $namespaceModel;
        if (!class_exists($modelPath)) {
            throw new \Exception("Model {$modelPath} not exist!");
        }
        return with(new $modelPath())->getTable();
    }

    /**
     * @param $namespaceModel
     * @return bool
     */
    public function isNamespaceModel($namespaceModel)
    {
        return str_contains($namespaceModel, "\\");
    }

    /**
     * Format bytes
     * @param $bytes
     * @param int $precision
     * @return string
     */
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }


    /**
     * Fetch queries value if key exist
     * @param $queries
     * @param $keyString
     * @return mixed
     */
    public function getValueIfExist($queries, $keyString)
    {
        foreach ($queries as $query) {
            if ($query->Variable_name == $keyString) {
                return $query->Value;
            }
        }
    }
}
