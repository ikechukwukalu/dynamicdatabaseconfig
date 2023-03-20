# DYNAMIC MAIL CONFIG

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ikechukwukalu/dynamicdatabaseconfig?style=flat-square)](https://packagist.org/packages/ikechukwukalu/dynamicdatabaseconfig)
[![Quality Score](https://img.shields.io/scrutinizer/quality/g/ikechukwukalu/dynamicdatabaseconfig/main?style=flat-square)](https://scrutinizer-ci.com/g/ikechukwukalu/dynamicdatabaseconfig/)
[![Code Quality](https://img.shields.io/codefactor/grade/github/ikechukwukalu/dynamicdatabaseconfig?style=flat-square)](https://www.codefactor.io/repository/github/ikechukwukalu/dynamicdatabaseconfig)
[![Github Workflow Status](https://img.shields.io/github/actions/workflow/status/ikechukwukalu/dynamicdatabaseconfig/dynamicdatabaseconfig.yml?branch=main&style=flat-square)](https://github.com/ikechukwukalu/dynamicdatabaseconfig/actions/workflows/dynamicdatabaseconfig.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ikechukwukalu/dynamicdatabaseconfig?style=flat-square)](https://packagist.org/packages/ikechukwukalu/dynamicdatabaseconfig)
[![Licence](https://img.shields.io/packagist/l/ikechukwukalu/dynamicdatabaseconfig?style=flat-square)](https://github.com/ikechukwukalu/dynamicdatabaseconfig/blob/main/LICENSE.md)

A laravel package that enables each user to send emails through your app using their own unique email configuration.

## REQUIREMENTS

- PHP 8.0+
- Laravel 9+

## STEPS TO INSTALL

``` shell
composer require ikechukwukalu/dynamicdatabaseconfig
```

- `php artisan vendor:publish --tag=ddc-migrations`
- `php artisan migrate`

### Hash Database Fields

``` shell
MAIL_FIELDS_HASH=true
```

### How To Use

``` php
use Illuminate\Support\Facades\Route;


Route::middleware(['dynamic.database.config'])->group(function () {
    Route::post('/', [\namespace\SomethingController::class, 'functionName']);
});

Route::post('/', [\namespace\SomethingController::class, 'functionName'])->middleware('dynamic.database.config');
```

### Model

```php
use Ikechukwukalu\Dynamicdatabaseconfig\Models\DatabaseConfiguration;

protected $hidden = [
    'name',
    'address',
    'driver',
    'host',
    'port',
    'encryption',
    'username',
    'password'
];
```

## NOTE

The default mail configuration will be used if a user does not have a custom mail configuration in place.

## PUBLISH CONFIG

- `php artisan vendor:publish --tag=ddc-config`

## LICENSE

The DDC package is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
