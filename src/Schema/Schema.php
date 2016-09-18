<?php
/**
 * Created by PhpStorm.
 * User: thedevsaddam
 * Date: 8/31/16
 * Time: 12:10 PM
 */

namespace Thedevsaddam\LaravelSchema\Schema;

use Thedevsaddam\LaravelSchema\Schema\Wrapper\MysqlWrapper;
use Thedevsaddam\LaravelSchema\Schema\Wrapper\PostgresWrapper;
use Thedevsaddam\LaravelSchema\Schema\Wrapper\SqliteWrapper;

class Schema extends BaseSchema
{
    public $databaseWrapper;
    public $headers = ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'];

    public function __construct()
    {
        parent::__construct();
        $this->switchConnection();
    }

    public function switchConnection()
    {
        switch ($this->connection) {
            case 'mysql':
                $this->databaseWrapper = new MysqlWrapper($this);
                break;
            case 'sqlite':
                $this->headers = ['CID', 'Field', 'Type', 'Null', 'Key', 'Default'];
                $this->databaseWrapper = new SqliteWrapper($this);
                break;
            case 'pgsql':
                $this->headers = ['Field', 'Type', 'Null', 'Key', 'Default'];
                $this->databaseWrapper = new PostgresWrapper($this);
                break;
            default:
                $this->databaseWrapper = new MysqlWrapper($this);
        }
    }
}