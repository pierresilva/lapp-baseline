# A trait to make Eloquent models translatable

This package contains a trait to make Eloquent models translatable. Translations are stored as json. There is no extra table needed to hold them.

Once the trait is installed on the model you can do these things:

```php
$newsItem = new NewsItem; // This is an Eloquent model
$newsItem
   ->setTranslation('name', 'en', 'Name in English')
   ->setTranslation('name', 'nl', 'Naam in het Nederlands')
   ->save();
   
$newsItem->name; // Returns 'Name in English' given that the current app locale is 'en'
$newsItem->getTranslation('name', 'nl'); // returns 'Naam in het Nederlands'

app()->setLocale('nl');

$newsItem->name; // Returns 'Naam in het Nederlands'
```

## Installation

You can install the package via composer:

``` bash
composer require spatie/laravel-translatable
```

The package will automatically register itself.

If you want to change add fallback_locale, you must publish the config file:
```
php artisan vendor:publish --provider="pierresilva\Translatable\TranslatableServiceProvider"
```

This is the contents of the published file:
```php
return [
  'fallback_locale' => 'en',
];
```


## Making a model translatable

The required steps to make a model translatable are:

- First, you need to add the `pierresilva\Translatable\HasTranslations`-trait.
- Next, you should create a public property `$translatable` which holds an array with all the names of attributes you wish to make translatable.
- Finally, you should make sure that all translatable attributes are set to the `text`-datatype in your database. If your database supports `json`-columns, use that.

Here's an example of a prepared model:

``` php
use Illuminate\Database\Eloquent\Model;
use pierresilva\Translatable\HasTranslations;

class NewsItem extends Model
{
    use HasTranslations;
    
    public $translatable = ['name'];
}
```

### Available methods

#### Getting a translation

The easiest way to get a translation for the current locale is to just get the property for the translated attribute.
For example (given that `name` is a translatable attribute):

```php
$newsItem->name;
```

You can also use this method:

```php
public function getTranslation(string $attributeName, string $locale) : string
```

This function has an alias named `translate`.

#### Getting all translations

You can get all translations by calling `getTranslations()` without an argument:

```php
$newsItem->getTranslations();
```

Or you can use the accessor

```php
$yourModel->translations
```

#### Setting a translation
The easiest way to set a translation for the current locale is to just set the property for a translatable attribute.
For example (given that `name` is a translatable attribute):

```php
$newsItem->name = 'New translation';
```

To set a translation for a specific locale you can use this method:

``` php
public function setTranslation(string $attributeName, string $locale, string $value)
```

To actually save the translation, don't forget to save your model.

```php
$newsItem->setTranslation('name', 'en', 'Updated name in English');

$newsItem->save();
```

#### Validation

- if you want to validate an attribute for uniqueness before saving/updating the db, you might want to have a look at [laravel-unique-translation](https://github.com/codezero-be/laravel-unique-translation) which is made specifically for *laravel-translatable*.

#### Forgetting a translation

You can forget a translation for a specific field:
``` php
public function forgetTranslation(string $attributeName, string $locale)
```

You can forget all translations for a specific locale:
``` php
public function forgetAllTranslations(string $locale)
```

#### Getting all translations in one go

``` php
public function getTranslations(string $attributeName): array
```

#### Setting translations in one go

``` php
public function setTranslations(string $attributeName, array $translations)
```

Here's an example:

``` php
$translations = [
   'en' => 'Name in English',
   'nl' => 'Naam in het Nederlands'
];

$newsItem->setTranslations('name', $translations);
```

### Events

#### TranslationHasBeenSet
Right after calling `setTranslation` the `pierresilva\Translatable\Events\TranslationHasBeenSet`-event will be fired.

It has these properties:
```php
/** @var \Illuminate\Database\Eloquent\Model */
public $model;

/** @var string  */
public $attributeName;

/** @var string  */
public $locale;

public $oldValue;
public $newValue;
```

### Creating models

You can immediately set translations when creating a model. Here's an example:
```php
NewsItem::create([
   'name' => [
      'en' => 'Name in English'
      'nl' => 'Naam in het Nederlands'
   ],
]);
```

### Querying translatable attributes

If you're using MySQL 5.7 or above, it's recommended that you use the json data type for housing translations in the db.
This will allow you to query these columns like this:

```php
NewsItem::where('name->en', 'Name in English')->get();
```

## Testing

```bash
composer test
```

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

We got the idea to store translations as json in a column from [Mohamed Said](https://github.com/themsaid). Parts of the readme of [his multilingual package](https://github.com/themsaid/laravel-multilingual) were used in this readme.

## Support us

pierresilva is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

Does your business depend on our contributions? Reach out and support us on [Patreon](https://www.patreon.com/spatie). 
All pledges will be dedicated to allocating workforce on maintenance and new awesome stuff.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
