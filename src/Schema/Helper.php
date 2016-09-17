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
        if(!class_exists($modelPath)){
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
        return (strpos($namespaceModel, "\\") !== false) ? true : false;
    }

}