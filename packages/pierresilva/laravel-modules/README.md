# Laravel Modules


Laravel Modules is a simple package to allow the means to separate your Laravel 5.7+ application out into modules. Each module is completely self-contained allowing the ability to simply drop a module in for use.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code.

## Documentation
You will find user friendly and updated documentation in the wiki here: [Laravel Modules Wiki](https://github.com/pierresilva/laravel-modules/wiki)

## Quick Installation
Begin by installing the package through Composer.

```
composer require laravel/modules
```

Once this operation is complete, simply add both the service provider and facade classes to your project's `config/app.php` file:

#### Service Provider

```php
pierresilva\Modules\ModulesServiceProvider::class,
```

#### Facade

```php
'Module' => pierresilva\Modules\Facades\Module::class,
```

And that's it! With your coffee in reach, start building out some awesome modules!

## Tests

Run the tests with:

``` bash
vendor/bin/phpunit
```

## Credits


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
