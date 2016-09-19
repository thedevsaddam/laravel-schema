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
"thedevsaddam/laravel-schema": "^1.0"
```
Open your terminal and hit the command
```bash
composer update
```

Add the following line to config/app.php file

```bash
Thedevsaddam\LaravelSchema\LaravelSchemaServiceProvider::class,
```

<hr/>

### **Available commands / Features**
1. `php artisan schema:help` Display the available commands and usages
1. `php artisan schema:simple` Display overall tables with total rows count
1. `php artisan schema:list` Display all the available tables schema information in list (_please see details below_).
1. `php artisan schema:show` Display all the available tables schema information in tabular form (_please see details below_).
1. `php artisan schema:table --t=yourTableName or --t=Namespace\\Model` Display a table's paginated data (_please see details below_).
1. `php artisan schema:query --q="wirte your raw sql query in double quote"` Perform a sql query.

<hr/>

### **Usage in details**
To see the schema information in tabular form
```bash
 php artisan schema:show
```

Schema information in tabular form

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/tabular.png)

If you want to see a specific table then pass table name or Namespace\\\Model
```bash
 php artisan schema:show --t=tableName or --t=Namespace\\Model
```

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/tabular-single.png)


_Note: Same condition will be applied for tables **list** view_

To see the schema information in list
```bash
 php artisan schema:list
```

Schema information in list

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/list.png)

Avaliable Options in **show** and **list**:

* `--t=tableName` or `-t tableName` to provide table name or Namespace\\Model
* `--c=connectionName` or `-c connectionName` to provide connection name

<hr/>

To see the tables name only
```bash
 php artisan schema:simple
```

Tables name with rows count

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/simple.png)

_Note: You may pass `--c=connectionName` or `-c connectionName` to display a specific connection schema info_

<hr/>

To see the table definition
```bash
 php artisan schema:table --t=tableName or --t=Namespace\\Model
```

Table definition with default page and limit

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/table-pagination.png)

Avaliable Options in **table**:

* `--t=tableName` or `-t tableName` to provide table name or Namespace\\\Model (e.g: --t=App\\\User or --t=users).
* `--p=pageNumber` or `-p PageNumber` to provide current page which you want to see
* `--o=orderBy` or `-o orderBy` to provide orderBy clause against a column (e.g: --o=id:desc or --o=id:asc [default asc]).
* `--l=rowsLimitPerPage` or `-l rowsLimitPerPage` to provide number of rows you want to display (e.g: --l=20).
* `--c=connectionName` or `-c connectionName` to provide connection name
* `--w=widthOfTableCell` or `-w widthOfTableCell` to provide character length of cell to show in tables (numeric [default=10]).


```bash
php artisan schema:table --t=countries --p=4 --o=id:desc --l=25
```

<hr/>

To perform raw sql query
```bash
 php artisan schema:query --r="your sql query"
```

Query result will be dumped in console

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/raw-query.png)

Avaliable Options in **query**:

* `--r=yourRawQuery` or `-r yourRawQuery` to provide your raw sql query (e.g: --r="select * from someTable limit 20").
* `--c=connectionName` or `-c connectionName` to provide connection name (e.g: --c=mysql or -c sqlite)

<hr/>

### **License**
The **laravel-schema** is a open-source software licensed under the [MIT License](LICENSE.md).

_Thank you :)_
