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

### **Available commands / Features**
1. `php artisan schema:help` Display the available commands and usages
1. `php artisan schema:simple` Display overall tables with total rows count
1. `php artisan schema:list` Display all the available tables schema information in list (_please see details below_).
1. `php artisan schema:show` Display all the available tables schema information in tabular form (_please see details below_).
1. `php artisan schema:table yourTableName or Namespace\\Model` Display a table's paginated data (_please see details below_).
1. `php artisan schema:query "wirte your raw sql query in double quote"` Perform a sql query.

### **Usage in details**
To see the schema information in tabular form
```bash
 php artisan schema:show
```

Schema information in tabular form

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/tabular.png)

If you want to see a specific table then pass table  name as first argument
```bash
 php artisan schema:show tableName or Namespace\\Model
```

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/tabular-single.png)


Note: Same condition will be applied for tables list view

To see the schema information in list
```bash
 php artisan schema:list
```

Schema information in list

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/list.png)

To see the tables name only
```bash
 php artisan schema:simple
```

Tables name with rows count

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/simple.png)


To see the table definition
```bash
 php artisan schema:table tableName or Namespace\\Model
```

Table definition with default page and limit

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/table-pagination.png)

Note: Provide first argument as **page number** (integer [default is 1]), second argument **columnName:order** for ordering the table(order=asc or desc [default is 'ascending order']), third argument as **limit** (integer [default is 15]).
See the example below:

**You can pass Namespace\\Model instead of table name**

```bash
php artisan schema:table countries 4 id:desc 25
```

To perform raw sql query
```bash
 php artisan schema:query "your sql query"
```

Query result will be dumped in console

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/raw-query.png)

### TODO
1. Support for SQL Server
1. Support for multiple connection
1. Code refactoring

### **License**
The **laravel-schema** is a open-source software licensed under the [MIT License](LICENSE.md).

Thank you :)
