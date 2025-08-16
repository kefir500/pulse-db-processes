# Laravel Pulse DB Processes

[![Latest Version on Packagist](https://img.shields.io/packagist/v/kefir500/pulse-db-processes.svg?style=flat-square)](https://packagist.org/packages/kefir500/pulse-db-processes)

Database process monitoring for [Laravel Pulse](https://laravel.com/docs/pulse).

## Installation

Install the package via Composer:

```bash
composer require kefir500/pulse-db-processes
```

Optionally, you can publish the views:

```bash
php artisan vendor:publish --tag="pulse-db-processes-views"
```

## Usage

This package currently supports **MySQL** and **MariaDB**.

You can add the cards below to the Pulse dashboard in
[`dashboard.blade.php`](https://laravel.com/docs/pulse#dashboard-customization).

### 🧩 Database Process List

This card displays a list of current database processes running on the server:

```blade
<livewire:db-process-list />
```

You can also specify custom database connections:

```blade
<livewire:db-process-list connection="default" />
<livewire:db-process-list connection="client" />
```

### 🧩 Database Process Count

This card displays a line chart of the database process count over time:

```blade
<livewire:db-process-count />
```

To capture database process count metrics, add the recorder
to the `recorders` section of your Pulse config in
[`config/pulse.php`](https://laravel.com/docs/pulse#configuration):

```php
Kefir500\PulseDbProcesses\Recorders\DbProcessCount::class => [
    'connections' => ['default', 'client'],
],
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
