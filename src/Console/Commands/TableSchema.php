<?php

namespace Thedevsaddam\LaravelSchema\Console\Commands;

use Illuminate\Console\Command;
use Thedevsaddam\LaravelSchema\Schema\Helper;
use Thedevsaddam\LaravelSchema\Schema\Schema;
use Symfony\Component\Console\Input\InputOption;


class TableSchema extends Command
{
    use Helper;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'schema:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display connected database schema table definition';

    private $schema;

    public function __construct(Schema $schema)
    {
        parent::__construct();
        $this->schema = $schema;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->showSchemaDefinitionInTable();
    }

    /**
     * Display schema definition in table
     * @return bool
     */
    public function showSchemaDefinitionInTable()
    {
        //change connection if provide
        if ($this->option('c')) {
            $this->schema->setConnection($this->option('c'));
            $this->schema->switchWrapper();
        }

        $tables = $this->schema->databaseWrapper->getTables();

        if (!count($tables)) {
            $this->warn('Database does not contain any table');
            return false;
        }

        $tableName = $this->option('t');
        if (empty($tableName)) {
            $this->warn('Table name is required!');
            return false;
        }

        if ($this->isNamespaceModel($tableName)) {
            $tableName = $this->tableNameFromModel($tableName);
        }

        if (!in_array($tableName, $tables)) {
            $this->warn('Table name is not correct!');
            return false;
        }

        $page = (!empty($this->option('p'))) ? $this->option('p') : 1;
        $limit = (!empty($this->option('l'))) ? $this->option('l') : 15;
        $orderBy = (!empty($this->option('o'))) ? $this->option('o') : null;
        $select = (!empty($this->option('s'))) ? $this->option('s') : null;
        $columns = $this->schema->databaseWrapper->getColumns($tableName);
        if (str_contains($select, ',')) {
            $selectedColumns = explode(',', $select);
        } else {
            $selectedColumns[] = $select;
        }

        $headers = [];
        foreach ($columns as $column) {
            foreach ($selectedColumns as $selected) {
                if (!$select) {
                    $headers[] = $column['Field'];
                } else {
                    if (($selected == $column['Field']))
                        $headers[] = $column['Field'];
                }
            }
        }
        $attributeName = null;
        $order = null;
        if (null !== $orderBy) {
            if (strpos($orderBy, ':') !== false) {
                $orderBy = explode(':', $orderBy);
            }
            $attributeName = $orderBy[0];
            if (isset($orderBy[1])) {
                $order = ('desc' == strtolower($orderBy[1])) ? 'DESC' : 'ASC';
            }
            if (!in_array($attributeName, $headers)) {
                $this->warn("There is no column named '$attributeName'. Please provide a correct column name for ordering!");
                return false;
            }
        }

        $rows = $this->schema->getPaginatedData($tableName, $page, $limit, $attributeName, $order)['data'];
        $body = $this->makeTableBody($headers, $rows);
        $rowsCount = $this->schema->getTableRowCount($tableName);
        $this->info($tableName . ' (rows ' . $rowsCount . ')');

        $this->table($headers, $body);
        return false;
    }

    /**
     * Make formatted body for table
     * @param $headers
     * @param $rows
     * @return array
     */
    private function makeTableBody($headers, $rows)
    {
        $body = [];
        $tableCellWidth = ($this->option('w')) ? $this->option('w') : 10;
        for ($i = 0; $i < count($rows); $i++) {
            $row = [];
            for ($j = 0; $j < count($headers); $j++) {
                $column = $headers[$j];
                $row[$j] = str_limit($rows[$i]->$column, $tableCellWidth, '');
            }
            $body[$i] = $row;
        }
        return $body;
    }

    protected function getOptions()
    {
        return [
            ['t', 't', InputOption::VALUE_OPTIONAL, 'Table name'],
            ['c', 'c', InputOption::VALUE_OPTIONAL, 'Connection name'],
            ['p', 'p', InputOption::VALUE_OPTIONAL, 'Page number'],
            ['l', 'l', InputOption::VALUE_OPTIONAL, 'Limit per page'],
            ['o', 'o', InputOption::VALUE_OPTIONAL, 'Order result against attribute'],
            ['w', 'w', InputOption::VALUE_OPTIONAL, 'Width of the table cell in char'],
            ['s', 's', InputOption::VALUE_OPTIONAL, 'Selected columns name'],
        ];
    }

}
