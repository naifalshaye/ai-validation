## Laravel Spam Validation Rule using ChatGPT

An efficient Laravel validation rule using ChatGPT to identify and filter spam input.

### Requirements
- PHP 8.1 or higher
- Laravel 9.0 or higher

## Installation

You can install the package into a Laravel app via composer:

```bash
composer require naif/spam-validation-rule
```

## Usage

In field validation rule
```
use Naif\SpamValidationRule\SpamRule;

$request->validate([
    'field_name' => [new SpamRule],
]);
```


## Support:
naif@naif.io

https://naif.io

Bug Tracker:

https://github.com/naifalshaye/spam-validation-rule/issues/new

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.