# DYNAMIC MAIL CONFIG

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ikechukwukalu/dynamicmailconfig?style=flat-square)](https://packagist.org/packages/ikechukwukalu/dynamicmailconfig)
[![Quality Score](https://img.shields.io/scrutinizer/quality/g/ikechukwukalu/dynamicmailconfig/main?style=flat-square)](https://scrutinizer-ci.com/g/ikechukwukalu/dynamicmailconfig/)
[![Code Quality](https://img.shields.io/codefactor/grade/github/ikechukwukalu/dynamicmailconfig?style=flat-square)](https://www.codefactor.io/repository/github/ikechukwukalu/dynamicmailconfig)
[![Github Workflow Status](https://img.shields.io/github/actions/workflow/status/ikechukwukalu/dynamicmailconfig/dynamicmailconfig.yml?branch=main&style=flat-square)](https://github.com/ikechukwukalu/dynamicmailconfig/actions/workflows/dynamicmailconfig.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ikechukwukalu/dynamicmailconfig?style=flat-square)](https://packagist.org/packages/ikechukwukalu/dynamicmailconfig)
[![Licence](https://img.shields.io/packagist/l/ikechukwukalu/dynamicmailconfig?style=flat-square)](https://github.com/ikechukwukalu/dynamicmailconfig/blob/main/LICENSE.md)

A laravel package that enables each user to send emails through your app using their own unique email configuration.

## REQUIREMENTS

- PHP 8.0+
- Laravel 9+

## STEPS TO INSTALL

``` shell
composer require ikechukwukalu/dynamicmailconfig
```

- `php artisan vendor:publish --tag=dmc-migrations`
- `php artisan migrate`

### Hash Database Fields

``` shell
MAIL_FIELDS_HASH=true
```

### How To Use

``` php
use Illuminate\Support\Facades\Route;


Route::middleware(['dynamic.mail.config'])->group(function () {
    Route::post('/', [\namespace\SomethingController::class, 'functionName']);
});

Route::post('/', [\namespace\SomethingController::class, 'functionName'])->middleware('dynamic.mail.config');
```

### Model

```php
use Ikechukwukalu\Dynamicmailconfig\Models\UserEmailConfiguration;

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

- `php artisan vendor:publish --tag=dmc-config`

## LICENSE

The DMC package is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
