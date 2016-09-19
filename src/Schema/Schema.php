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
use Thedevsaddam\LaravelSchema\Schema\Wrapper\SqlServerWrapper;

class Schema extends BaseSchema
{
    public $databaseWrapper;
    public $headers = ['Field', 'Type', 'Null', 'Key', 'Default', 'Extra'];

    public function __construct()
    {
        parent::__construct();
        $this->switchWrapper();
    }

    public function switchWrapper()
    {
        $driverName = $this->database->getDriverName();
        switch ($driverName) {
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
            case 'sqlsrv':
                $this->headers = ['Field', 'Type', 'Null', 'Key', 'Default', 'Char max len'];
                $this->databaseWrapper = new SqlServerWrapper($this);
                break;
            default:
                $this->databaseWrapper = new MysqlWrapper($this);
        }
    }
}
