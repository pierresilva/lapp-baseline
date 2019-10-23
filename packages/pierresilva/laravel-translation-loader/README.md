# Store your language lines in the database

In a vanilla Laravel or Lumen installation you can use [language files](https://laravel.com/docs/5.6/localization) to localize your app. This package will enable the translations to be stored in the database. You can still use all the features of [the `trans` function](https://laravel.com/docs/5.6/localization#retrieving-translation-strings) you know and love.

```php
trans('messages.welcome', ['name' => 'dayle']);
```

You can even mix using language files and the database. If a translation is present in both a file and the database, the database version will be returned.

Want to use a different source for your translations? No problem! The package is [easily extendable](https://github.com/pierresilva/laravel-translation-loader#creating-your-own-translation-providers).

## Installation

You can install the package via composer:

``` bash
composer require pierresilva/laravel-translation-loader
```

In `config/app.php` (Laravel) or `bootstrap/app.php` (Lumen) you should replace Laravel's translation service provider

```php
Illuminate\Translation\TranslationServiceProvider::class,
```

by the one included in this package:

```php
pierresilva\TranslationLoader\TranslationServiceProvider::class,
```

You must publish and run the migrations to create the `language_lines` table:

```bash
php artisan vendor:publish --provider="pierresilva\TranslationLoader\TranslationServiceProvider" --tag="migrations"
php artisan migrate
```

Optionally you could publish the config file using this command.

```bash
php artisan vendor:publish --provider="pierresilva\TranslationLoader\TranslationServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [

    /*
     * Language lines will be fetched by these loaders. You can put any class here that implements
     * the pierresilva\TranslationLoader\TranslationLoaders\TranslationLoader-interface.
     */
    'translation_loaders' => [
        pierresilva\TranslationLoader\TranslationLoaders\Db::class,
    ],

    /*
     * This is the model used by the Db Translation loader. You can put any model here
     * that extends pierresilva\TranslationLoader\LanguageLine.
     */
    'model' => pierresilva\TranslationLoader\LanguageLine::class,

    /*
     * This is the translation manager which overrides the default Laravel `translation.loader`
     */
    'translation_manager' => pierresilva\TranslationLoader\TranslationLoaderManager::class,

];
```

> **Note:** publishing assets doesn't work out of the box in Lumen. Instead you have to copy the files from the repo.

## Usage

You can create a translation in the database by creating and saving an instance of the `pierresilva\TranslationLoader\LanguageLine`-model:

```php
use pierresilva\TranslationLoader\LanguageLine;

LanguageLine::create([
   'group' => 'validation',
   'key' => 'required',
   'text' => ['en' => 'This is a required field', 'nl' => 'Dit is een verplicht veld'],
]);
```

You can fetch the translation with [Laravel's default `trans` function](https://laravel.com/docs/5.3/localization#retrieving-language-lines):

```php
trans('validation.required'); // returns 'This is a required field'

app()->setLocale('nl');

trans('validation.required'); // returns 'Dit is een verplicht veld'
```

You can still keep using the default language files as well. If a requested translation is present in both the database and the language files, the database version will be returned.

## Creating your own translation providers

This package ships with a translation provider than can fetch translations from the database. If you're storing your translations in a yaml-file, a csv-file, or ... you can easily extend this package by creating your own translation provider.

A translation provider can be any class that implements the `pierresilva\TranslationLoader\TranslationLoaders\TranslationLoader`-interface. It contains only one method:

```php
namespace pierresilva\TranslationLoader\TranslationLoaders;

interface TranslationLoader
{
    /*
     * Returns all translations for the given locale and group.
     */
    public function loadTranslations(string $locale, string $group): array;
}
```

Translation providers can be registered in the `translation_loaders` key of the config file.

## Testing

``` bash
composer test
```

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
