<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 8/31/16
 * Time: 12:10 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema;

use Thedevsaddam\LaravelSchema\Schema\Wrapper\MysqlWrapper;
use Thedevsaddam\LaravelSchema\Schema\Wrapper\SqliteWrapper;

class Schema extends BaseSchema
{
    public $databaseWrapper;

    public function __construct()
    {
        parent::__construct();
        switch ($this->connection) {
            case 'mysql':
                $this->databaseWrapper = new MysqlWrapper($this);
                break;
            case 'sqlite':
                $this->databaseWrapper = new SqliteWrapper($this);
                break;
            default:
                $this->databaseWrapper = new MysqlWrapper($this);
        }
    }
}