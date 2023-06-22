# DYNAMIC DATABASE CONFIG

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ikechukwukalu/dynamicdatabaseconfig?style=flat-square)](https://packagist.org/packages/ikechukwukalu/dynamicdatabaseconfig)
[![Quality Score](https://img.shields.io/scrutinizer/quality/g/ikechukwukalu/dynamicdatabaseconfig/main?style=flat-square)](https://scrutinizer-ci.com/g/ikechukwukalu/dynamicdatabaseconfig/)
[![Code Quality](https://img.shields.io/codefactor/grade/github/ikechukwukalu/dynamicdatabaseconfig?style=flat-square)](https://www.codefactor.io/repository/github/ikechukwukalu/dynamicdatabaseconfig)
[![Vulnerability](https://img.shields.io/snyk/vulnerabilities/github/ikechukwukalu/dynamicdatabaseconfig?style=flat-square)](https://security.snyk.io/package/composer/ikechukwukalu%2Fdynamicdatabaseconfig)
[![Github Workflow Status](https://img.shields.io/github/actions/workflow/status/ikechukwukalu/dynamicdatabaseconfig/dynamicdatabaseconfig.yml?branch=main&style=flat-square)](https://github.com/ikechukwukalu/dynamicdatabaseconfig/actions/workflows/dynamicdatabaseconfig.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ikechukwukalu/dynamicdatabaseconfig?style=flat-square)](https://packagist.org/packages/ikechukwukalu/dynamicdatabaseconfig)
[![Licence](https://img.shields.io/packagist/l/ikechukwukalu/dynamicdatabaseconfig?style=flat-square)](https://github.com/ikechukwukalu/dynamicdatabaseconfig/blob/main/LICENSE.md)

This laravel package helps you dynamically set more database configurations through the `.env` file or `database`.

## REQUIREMENTS

- PHP 8.0+
- Laravel 9+

## STEPS TO INSTALL

``` shell
composer require ikechukwukalu/dynamicdatabaseconfig
```

### Introduction

The need for this package came up when I once handled an already existing project that, due to certain constraints, had 9 databases implemented for each country their application was being utilised. This application also had a central database that was used by every country as well.

The `config/database` file wasn't pretty. I'd prefer to have all configurations within the `.env` file only. The Big question was, what if the databases required grew to 19? These were the problems, both pending and existing that needed a clean hack/solution.

### Middlewares

- `env.database.config`
- `dynamic.database.config`

#### `Env.database.config` Middleware

This middleware fetches database configurations from the `.env` file using postfixes like `ONE`. This dynamically declares an additional database connection for your laravel application.

- Sample env config

``` shell
DB_HOST_ONE=127.0.0.1
DB_PORT_ONE=3306
DB_DATABASE_ONE=second_db
DB_USERNAME_ONE=root
DB_PASSWORD_ONE=
```

- Sample middleware implementation

``` php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * mysql is the type of relational database connection being replicated - $database
 * mysql_1 is the new connection name - $name
 * ONE is the postfix - $postfix
 */

Route::middleware(['env.database.config:mysql,mysql_1,ONE'])->group(function () {
    Route::post('/user', function (Request $request) {
        /**
         * $request->_db_connection === 'mysql_1'
         */
        return \App\Models\User::on('mysql_1')->find(1);
    });
});

Route::post('/user', function (Request $request) {
        /**
         * $request->_db_connection === 'mysql_1'
         */
        return \App\Models\User::on('mysql_1')->find(1);
})->middleware('env.database.config:mysql,mysql_1,ONE');
```

You would not need to add a postfix, `ONE`, parameter to the middleware for the `$postFix` variable if you simply set the following session value `session(config('dynamicdatabaseconfig.session_postfix'))`, but when a postfix parameter has been set, it will be used instead of the session value.

#### `Dynamic.database.config` Middleware

This middleware fetches database configurations from the `database_configurations` table within the primary migration database. It utilises a unique `$ref` variable. It's recommended that the unique `$ref` variable should be human readable, that way it becomes easier to run the package's console commands for running migrations. This will also dynamically declare an additional database connection for your laravel application.

- Model file

``` php
use Ikechukwukalu\Dynamicdatabaseconfig\Models\DatabaseConfiguration;

protected $hidden = [
        'ref',
        'name',
        'database',

        /**
         * Accepts only arrays
         */
        'configuration'
];
```

- Sample eloquent database insert

```php
$countries = ['nigeria', 'ghana', 'togo', 'kenya'];
$config = \Config::get('database.connections.mysql');

foreach ($countries as $country) {
    $config['database'] = $country . '_db';
    DatabaseConfiguration::firstOrCreate(
    ['ref' => $country],
    [
        'ref' => $country,
        'name' => 'mysql_' . $country,
        'database' => 'mysql',
        'configuration' => $config
    ]);
}
```

- Sample middleware implementation

``` php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * nigeria is $ref value
 */

Route::middleware(['dynamic.database.config:nigeria'])->group(function () {
    Route::post('/user', function (Request $request) {
        /**
         * $request->_db_connection === 'mysql_nigeria'
         */
        return \App\Models\User::on('mysql_nigeria')->find(1);
    });
});

Route::post('/user', function (Request $request) {
        /**
         * $request->_db_connection === 'mysql_nigeria'
         */
        return \App\Models\User::on('mysql_nigeria')->find(1);
})->middleware('dynamic.database.config:nigeria');
```

You would not need to add a ref, `nigeria`, parameter to the middleware for the `$ref` variable if you simply set the following session value `session(config('dynamicdatabaseconfig.session_ref'))`, but when a ref parameter has been set, it will be used instead of the session value.

By default, the values stored within the `configuration` field will be hashed, but you can adjust this from the `.env` file by setting `DB_CONFIGURATIONS_HASH=false`.

### Migration

It's compulsory to first migrate laravel's initial database.

- `php artisan migrate`

### Other Migrations

- Default migrations
- Isolated migrations

#### Default Migrations

This will only migrate files within laravel's default migration path `database/migrations`

``` shell
php artisan env:migrate mysql mysql_1 ONE

php artisan dynamic:migrate nigeria
```

#### Isolated Migrations

This will only migrate files within the specified migration path `database/migrations/folder`

``` shell
php artisan env:migrate mysql mysql_1 ONE --path=database/migrations/folder

php artisan dynamic:migrate nigeria --path=database/migrations/folder
```

#### Both Migrations

Running the migrations as displayed below will result in the respective database having the migrated data from migrations within `database/migrations` and `database/migrations/folder`.

``` shell
php artisan env:migrate mysql mysql_1 ONE
php artisan env:migrate mysql mysql_1 ONE --path=database/migrations/folder

php artisan dynamic:migrate nigeria
php artisan dynamic:migrate nigeria --path=database/migrations/folder
```

### Database Seeding

``` shell
php artisan env:migrate mysql mysql_1 ONE --seed
php artisan env:migrate mysql mysql_1 ONE --seeder=DatabaseSeederOne
php artisan env:migrate mysql mysql_1 ONE --seeder=DatabaseSeederOne  --path=database/migrations/folder

php artisan dynamic:migrate nigeria --seed
php artisan dynamic:migrate nigeria --seeder=DatabaseSeederNigeria
php artisan dynamic:migrate nigeria --seeder=DatabaseSeederNigeria  --path=database/migrations/folder
```

### Re-runing Migrations Afresh

``` shell
php artisan env:migrate mysql mysql_1 ONE --fresh
php artisan env:migrate mysql mysql_1 ONE --fresh --seed
php artisan env:migrate mysql mysql_1 ONE --fresh --seeder=DatabaseSeederOne
php artisan env:migrate mysql mysql_1 ONE --path=database/migrations/folder --fresh
php artisan env:migrate mysql mysql_1 ONE --path=database/migrations/folder --fresh --seeder=DatabaseSeederOne

php artisan dynamic:migrate nigeria --fresh
php artisan dynamic:migrate nigeria --fresh --seed
php artisan dynamic:migrate nigeria --fresh --seeder=DatabaseSeederNigeria
php artisan dynamic:migrate nigeria --path=database/migrations/folder --fresh
php artisan dynamic:migrate nigeria --path=database/migrations/folder --fresh --seeder=DatabaseSeederNigeria
```

### Refreshing Migrations

``` shell
php artisan env:migrate mysql mysql_1 ONE --refresh
php artisan env:migrate mysql mysql_1 ONE --refresh --seed
php artisan env:migrate mysql mysql_1 ONE --refresh --seeder=DatabaseSeederOne
php artisan env:migrate mysql mysql_1 ONE --path=database/migrations/folder --refresh
php artisan env:migrate mysql mysql_1 ONE --path=database/migrations/folder --refresh --seeder=DatabaseSeederOne

php artisan dynamic:migrate nigeria --refresh
php artisan dynamic:migrate nigeria --refresh --seed
php artisan dynamic:migrate nigeria --refresh --seeder=DatabaseSeederNigeria
php artisan dynamic:migrate nigeria --path=database/migrations/folder --refresh
php artisan dynamic:migrate nigeria --path=database/migrations/folder --refresh --seeder=DatabaseSeederNigeria
```

### Rolling Back Migrations

``` shell
php artisan env:migrate mysql mysql_1 ONE --rollback
php artisan env:migrate mysql mysql_1 ONE --path=database/migrations/folder --rollback

php artisan dynamic:migrate nigeria --rollback
php artisan dynamic:migrate nigeria --path=database/migrations/folder --rollback
```

## NOTE

- A primary database is needed before any other database can be migrated.
- A database will be created if it does not exist.
- Each database will retain it's own independent `migration` table.
- It's recommended that you do not publish the package's migration file, unless you want the `database_configurations` table to be migrated into every extra database created when running **Default migrations**.

## PUBLISH MIGRATIONS

- `php artisan vendor:publish --tag=ddc-migrations`

## PUBLISH CONFIG

- `php artisan vendor:publish --tag=ddc-config`

## LICENSE

The DDC package is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
