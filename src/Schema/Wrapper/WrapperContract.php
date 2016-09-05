<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 9/4/16
 * Time: 9:30 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema\Wrapper;


interface WrapperContract
{
    public function getTables();

    public function getColumns($tableName);

    public function getSchema();

}