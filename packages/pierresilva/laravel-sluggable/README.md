# Generate slugs when saving Eloquent models

This package provides a trait that will generate a unique slug when saving any Eloquent model. 

```php
$model = new EloquentModel();
$model->name = 'activerecord is awesome';
$model->save();

echo $model->slug; // ouputs "activerecord-is-awesome"
```

The slugs are generated with Laravels `Str::slug` method, whereby spaces are converted to '-'.

pierresilva is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://spatie.be/opensource).

## Installation

You can install the package via composer:
``` bash
composer require spatie/laravel-sluggable
```

## Usage

Your Eloquent models should use the `pierresilva\Sluggable\HasSlug` trait and the `pierresilva\Sluggable\SlugOptions` class.

The trait contains an abstract method `getSlugOptions()` that you must implement yourself. 

Here's an example of how to implement the trait:

```php
<?php

namespace App;

use pierresilva\Sluggable\HasSlug;
use pierresilva\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;

class YourEloquentModel extends Model
{
    use HasSlug;
    
    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
```

Want to use multiple field as the basis for a slug? No problem!

```php
public function getSlugOptions() : SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom(['first_name', 'last_name'])
        ->saveSlugsTo('slug');
}
```

You can also pass a `callable` to `generateSlugsFrom`.

By default the package will generate unique slugs by appending '-' and a number, to a slug that already exists.

You can disable this behaviour by calling `allowDuplicateSlugs`.

```php
public function getSlugOptions() : SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug')
        ->allowDuplicateSlugs();
}
```

You can also put a maximum size limit on the created slug:

```php
public function getSlugOptions() : SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug')
        ->slugsShouldBeNoLongerThan(50);
}
```

The slug may be slightly longer than the value specified, due to the suffix which is added to make it unique.

You can also use a custom separator by calling `usingSeparator`

```php
public function getSlugOptions() : SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug')
        ->usingSeparator('_');
}
```

To set the language used by `Str::slug` you may call `usingLanguage`

```php
public function getSlugOptions() : SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug')
        ->usingLanguage('nl');
}
```

You can also override the generated slug just by setting it to another value than the generated slug.

```php
$model = EloquentModel:create(['name' => 'my name']); //slug is now "my-name"; 
$model->slug = 'my-custom-url';
$model->save(); //slug is now "my-custom-url"; 
```

If you don't want to create the slug when the model is initially created you can set use the `doNotGenerateSlugsOnCreate()` function.

```php
public function getSlugOptions() : SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug')
        ->doNotGenerateSlugsOnCreate();
}
```

Similarly, if you want to prevent the slug from being updated on model updates, call `doNotGenerateSlugsOnUpdate()`.

```php
public function getSlugOptions() : SlugOptions
{
    return SlugOptions::create()
        ->generateSlugsFrom('name')
        ->saveSlugsTo('slug')
        ->doNotGenerateSlugsOnUpdate();
}
```

This can be helpful for creating permalinks that don't change until you explicitly want it to.

```php
$model = EloquentModel:create(['name' => 'my name']); //slug is now "my-name"; 
$model->save();

$model->name = 'changed name';
$model->save(); //slug stays "my-name"
```

If you want to explicitly update the slug on the model you can call `generateSlug()` on your model at any time to make the slug according to your other options. Don't forget to `save()` the model to persist the update to your database.

## Testing

``` bash
composer test
```

## Credits

- [Freek Van der Herten](https://github.com/freekmurze)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
