Laravel Sentinel
===================

Laravel Sentinel brings a simple and light-weight role-based permissions system to Laravel's built in Auth system. Laravel Sentinel brings support for the following ACL structure:

- Every user can have zero or more roles.
- Every role can have zero or more permissions.

Permissions are then inherited to the user through the user's assigned roles.

The package follows the FIG standards PSR-1, PSR-2, and PSR-4 to ensure a high level of interoperability between shared PHP code. At the moment the package is not unit tested, but is planned to be covered later down the road.

Documentation
-------------
You will find user friendly documentation in the wiki here: [Laravel Sentinel Wiki](https://github.com/pierresilva/laravel-sentinel/wiki)

Quick Installation
------------------
Begin by installing the package through Composer. The best way to do this is through your terminal via Composer itself:

```
composer require pierresilva/laravel-sentinel
```

Once this operation is complete, simply add the service provider to your project's `config/app.php` file and run the provided migrations against your database.

### Service Provider
```php
pierresilva\Sentinel\SentinelServiceProvider::class
```

### Migrations
You'll need to run the provided migrations against your database. Publish the migration files using the `vendor:publish` Artisan command and run `migrate`:

```
php artisan vendor:publish
php artisan migrate
```

Awesome Sentinel
----------------
See what the awesome community behind Laravel Sentinel has built. Created something you'd like added? Send a pull-request or open an issue!
