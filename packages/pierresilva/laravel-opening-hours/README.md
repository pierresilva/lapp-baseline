# A helper to query and format a set of opening hours

With `pierresilva/opening-hours` you create an object that describes a business' opening hours, which you can query for `open` or `closed` on days or specific dates, or use to present the times per day.

A set of opening hours is created by passing in a regular schedule, and a list of exceptions.

```php
$openingHours = OpeningHours::create([
    'monday' => ['09:00-12:00', '13:00-18:00'],
    'tuesday' => ['09:00-12:00', '13:00-18:00'],
    'wednesday' => ['09:00-12:00'],
    'thursday' => ['09:00-12:00', '13:00-18:00'],
    'friday' => ['09:00-12:00', '13:00-20:00'],
    'saturday' => ['09:00-12:00', '13:00-16:00'],
    'sunday' => [],
    'exceptions' => [
        '2016-11-11' => ['09:00-12:00'],
        '2016-12-25' => [],
        '01-01' => [], // Recurring on each 1st of january
        '12-25' => ['09:00-12:00'], // Recurring on each 25th of december
    ],
]);
```

The object can be queried for a day in the week, which will return a result based on the regular schedule:

```php
// Open on Mondays:
$openingHours->isOpenOn('monday'); // true

// Closed on Sundays:
$openingHours->isOpenOn('sunday'); // false
```

It can also be queried for a specific date and time:

```php
// Closed because it's after hours:
$openingHours->isOpenAt(new DateTime('2016-09-26 19:00:00')); // false

// Closed because Christmas was set as an exception
$openingHours->isOpenAt(new DateTime('2016-12-25')); // false
```

It can also return arrays of opening hours for a week or a day:

```php
// OpeningHoursForDay object for the regular schedule
$openingHours->forDay('monday');

// OpeningHoursForDay[] for the regular schedule, keyed by day name
$openingHours->forWeek();

// Array of day with same schedule for the regular schedule, keyed by day name, days combined by working hours
$openingHours->forWeekCombined();

// OpeningHoursForDay object for a specific day
$openingHours->forDate(new DateTime('2016-12-25'));

// OpeningHoursForDay[] of all exceptions, keyed by date
$openingHours->exceptions();
```

It can also return the next open or close `DateTime` from a given `DateTime`.

```php
// The next open datetime is tomorrow morning, because we’re closed on 25th of December.
$nextOpen = $openingHours->nextOpen(new DateTime('2016-12-25 10:00:00')); // 2016-12-26 09:00:00

// The next open datetime is this afternoon, after the lunch break.
$nextOpen = $openingHours->nextOpen(new DateTime('2016-12-24 11:00:00')); // 2016-12-24 13:00:00


// The next close datetime is at noon.
$nextClose = $openingHours->nextClose(new DateTime('2016-12-24 10:00:00')); // 2016-12-24 12:00:00

// The next close datetime is tomorrow at noon, because we’re closed on 25th of December.
$nextClose = $openingHours->nextClose(new DateTime('2016-12-25 15:00:00')); // 2016-12-26 12:00:00
```

Read the usage section for the full api.

pierresilva is a webdesign agency based in Antwerp, Belgium. You'll find an overview of all our open source projects [on our website](https://pierresilva.be/opensource).

## Installation

You can install the package via composer:

``` bash
composer require pierresilva/opening-hours
```

## Usage

The package should only be used through the `OpeningHours` class. There are also three value object classes used throughout, `Time`, which represents a single time, `TimeRange`, which represents a period with a start and an end, and `openingHoursForDay`, which represents a set of `TimeRange`s which can't overlap.

### `pierresilva\OpeningHours\OpeningHours`

#### `OpeningHours::create(array $data): pierresilva\OpeningHours\OpeningHours`

Static factory method to fill the set of opening hours.

``` php
$openingHours = OpeningHours::create([
    'monday' => ['09:00-12:00', '13:00-18:00'],
    // ...
]);
```

Not all days are mandatory, if a day is missing, it will be set as closed.

#### `OpeningHours::fill(array $data): pierresilva\OpeningHours\OpeningHours`

The same as `create`, but non-static.

``` php
$openingHours = (new OpeningHours)->fill([
    'monday' => ['09:00-12:00', '13:00-18:00'],
    // ...
]);
```

#### `OpeningHours::forWeek(): pierresilva\OpeningHours\OpeningHoursForDay[]`

Returns an array of `OpeningHoursForDay` objects for a regular week.

```php
$openingHours->forWeek();
```

#### `OpeningHours::forWeekCombined(): array`

Returns an array of days. Array key is first day with same hours, array values are days that have the same working hours and `OpeningHoursForDay` object. 

```php
$openingHours->forWeekCombined();
```

#### `OpeningHours::forDay(string $day): pierresilva\OpeningHours\OpeningHoursForDay`

Returns an `OpeningHoursForDay` object for a regular day. A day is lowercase string of the english day name.

```php
$openingHours->forDay('monday');
```

#### `OpeningHours::forDate(DateTime $dateTime): pierresilva\OpeningHours\OpeningHoursForDay`

Returns an `OpeningHoursForDay` object for a specific date. It looks for an exception on that day, and otherwise it returns the opening hours based on the regular schedule.

```php
$openingHours->forDate(new DateTime('2016-12-25'));
```

#### `OpeningHours::exceptions(): pierresilva\OpeningHours\OpeningHoursForDay[]`

Returns an array of all `OpeningHoursForDay` objects for exceptions, keyed by a `Y-m-d` date string.

```php
$openingHours->exceptions();
```

#### `OpeningHours::isOpenOn(string $day): bool`

Checks if the business is open on a day in the regular schedule.

```php
$openingHours->isOpenOn('saturday');
```

#### `OpeningHours::isClosedOn(string $day): bool`

Checks if the business is closed on a day in the regular schedule.

```php
$openingHours->isClosedOn('sunday');
```

#### `OpeningHours::isOpenAt(DateTime $dateTime): bool`

Checks if the business is open on a specific day, at a specific time.

```php
$openingHours->isOpenAt(new DateTime('2016-26-09 20:00'));
```

#### `OpeningHours::isClosedAt(DateTime $dateTime): bool`

Checks if the business is closed on a specific day, at a specific time.

```php
$openingHours->isClosedAt(new DateTime('2016-26-09 20:00'));
```

#### `OpeningHours::isOpen(): bool`

Checks if the business is open right now.

```php
$openingHours->isOpen();
```

#### `OpeningHours::isClosed(): bool`

Checks if the business is closed right now.

```php
$openingHours->isClosed();
```

#### `nextOpen(DateTimeInterface $dateTime) : DateTime`

Returns next open DateTime from the given DateTime

```php
$openingHours->nextOpen(new DateTime('2016-12-24 11:00:00'));
```

#### `nextClose(DateTimeInterface $dateTime) : DateTime`

Returns next close DateTime from the given DateTime

```php
$openingHours->nextClose(new DateTime('2016-12-24 11:00:00'));
```

### `pierresilva\OpeningHours\OpeningHoursForDay`

This class is meant as read-only. It implements `ArrayAccess`, `Countable` and `IteratorAggregate` so you can process the list of `TimeRange`s in an array-like way.

### `pierresilva\OpeningHours\TimeRange`

Value object describing a period with a start and an end time. Can be casted to a string in a `H:i-H:i` format.

### `pierresilva\OpeningHours\Time`

Value object describing a single time. Can be casted to a string in a `H:i` format.

## Testing

``` bash
$ composer test
```

## Credits

- [Sebastian De Deyne](https://github.com/sebastiandedeyne)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
