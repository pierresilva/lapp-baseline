# Laravel/Lumen schema builder
Database designer & migration generator package for laravel & lumen.

## Installation
```php
composer require --dev pierresilva/schema-builder
```
Then register SchemaBuilderServiceProvider, for laravel on `providers` array in `config/app.php`,
```php
\pierresilva\SchemaBuilder\SchemaBuilderServiceProvider::class
```
or for lumen in `bootstrap/app.php`
```php
$app->register(\pierresilva\SchemaBuilder\SchemaBuilderServiceProvider::class);
```

Finally enable required routes via `.env`,
```php
SCHEMA_ROUTES_ENABLED=true
```
> NOTE: APP_ENV should be `local` to use this package.

## Usage
Navigate to `yoursite.com/schema-builder` and build your database schema, then use the export button to generate migration files.

> NOTE: Not all features of migration are supported yet. Feel free to submit any issues or pull requests.

## License
[MIT](https://github.com/pierresilva/laravel-schema-builder/LICENSE)
