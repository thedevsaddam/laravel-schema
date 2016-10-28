Laravel Schema
===================


This package will help to display database schema information from terminal.


----------

Installation
-------------
Via Composer

``` bash
$ composer require thedevsaddam/laravel-schema
```
Install manually (add the line to composer.json file)
``` bash
"thedevsaddam/laravel-schema": "^2.0"
```
Then open your terminal and hit the command
```bash
composer update
```

Add the following line to config/app.php file's _providers_ array

```php
Thedevsaddam\LaravelSchema\LaravelSchemaServiceProvider::class,
```
For _lumen_ open bootstrap/app.php and add the line below

```php
$app->register(Thedevsaddam\LaravelSchema\LaravelSchemaServiceProvider::class);
```

<hr/>

### **Available commands / Features**
1. `php artisan schema:help` Display the available commands and usages.
1. `php artisan schema:simple` Display overall tables with total rows count.
1. `php artisan schema:list` Display all the available tables. schema information in list (_please see details below_).
1. `php artisan schema:show` Display all the available tables schema information in tabular form (_please see details below_).
1. `php artisan schema:table --t=yourTableName or --t=Namespace\\Model` Display a table's paginated data (_please see details below_).
1. `php artisan schema:query --r="wirte your raw sql query in double quote"` Perform a sql query.
1. `php artisan schema:monitor` Display database server status.

<hr/>

### **Usage in details**
**Show Schema information in tabular form**
```bash
 php artisan schema:show
```

Example output: Schema information in tabular form

![Schema information in tabular form](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/tabular.png)

If you want to see a specific table then pass table name or Namespace\\\Model
```bash
 php artisan schema:show --t=tableName or --t=Namespace\\Model
```

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/tabular-single.png)


_Note: Same condition will be applied for tables **list** view_

**Show Schema information in List**
```bash
 php artisan schema:list
```

Example output: Schema information in list

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/list.png)

Avaliable Options in **show** and **list**:

* `--t=tableName` or `-t tableName` to provide table name or Namespace\\Model
* `--c=connectionName` or `-c connectionName` to provide connection name



**Show Table names and total rows**
```bash
 php artisan schema:simple
```

Example output: Tables name with rows count

![Tables name with rows count](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/simple.png)

_Note: You may pass `--c=connectionName` or `-c connectionName` to display a specific connection schema info_



**Show table definition**
```bash
 php artisan schema:table --t=tableName or --t=Namespace\\Model
```

Example output: Table definition with default page and limit

![Table definition with default page and limit](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/table-pagination.png)

Avaliable Options in **table**:

* `--t=tableName` or `-t tableName` to provide table name or Namespace\\\Model (e.g: --t=App\\\User or --t=users).
* `--p=pageNumber` or `-p PageNumber` to provide current page which you want to see
* `--o=orderBy` or `-o orderBy` to provide orderBy clause against a column (e.g: --o=id:desc or --o=id:asc [default asc]).
* `--l=rowsLimitPerPage` or `-l rowsLimitPerPage` to provide number of rows you want to display (e.g: --l=20).
* `--c=connectionName` or `-c connectionName` to provide connection name
* `--w=widthOfTableCell` or `-w widthOfTableCell` to provide character length of cell to show in tables (numeric [default=10]).
* `--s=columnName` to provide the column to display, you can provide comma (,) separated names to display (e.g: --s=name or --s=id,name).


```bash
php artisan schema:table --t=countries --p=4 --o=id:desc --l=25
```



**Perform raw sql query**
```bash
 php artisan schema:query --r="your sql query"
```

Example output: Query result will be dumped in console

![Query result will be dumped in console](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/raw-query.png)

Avaliable Options in **query**:

* `--r=yourRawQuery` or `-r yourRawQuery` to provide your raw sql query (e.g: --r="select * from someTable limit 20").
* `--c=connectionName` or `-c connectionName` to provide connection name (e.g: --c=mysql or -c sqlite)



**Monitor database server**
```bash
 php artisan schema:monitor
```

Example output: Showing the database status

![Schema information in tabular form](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/monitoring-schema.png)

You can pass --i=integerNumber as refresh time interval and --c=ConnectionName as well
```bash
 php artisan schema:monitor --i=3 --c=secondaryDatabase
```

<hr/>

### **License**
The **laravel-schema** is a open-source software licensed under the [MIT License](LICENSE.md).

_Thank you :)_
