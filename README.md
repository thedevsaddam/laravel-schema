Laravel Schema
===================


This package will help to display database schema information from terminal.

----------


Installation
-------------
Via Composer

``` bash
$ composer require thedevsaddam/laravel-schema 1.0
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

### **Usage**
To see the schema information in tabular form
```bash
 php artisan schema:show
```

Schema information in tabular form

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/tabular.png)

To see the schema information in list
```bash
 php artisan schema:list
```

Schema information in list

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/list.png)

To perform raw sql query
```bash
 php artisan schema:query "your sql query"
```

Query result will be dumped in console

![database info commandline](https://raw.githubusercontent.com/thedevsaddam/laravel-schema/master/screenshots/raw-query.png)

Note: Multiple database facility coming soon...

Thank you :)