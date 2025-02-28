# Laravel Email Masking Package

This package provides a simple way to mask email addresses in your Laravel applications.

## Installation

```bash
composer require Tchilly/EmailMasking
```

## Usage

### In your models

```php
<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tchilly\EmailMasking\Traits\HasMaskedEmail;

class User extends Authenticatable
{
    use HasMaskedEmail;

    // Make sure to add 'email_masked' to your $appends array
    // if you want it included in your model's array/JSON form
    protected $appends = ['email_masked'];
}
```

Then you can access the masked email as a property:

```php
$user = User::first();
echo $user->email_masked; // j***@example.com
```

You can also use the method with custom options:

```php
$user = User::first();
echo $user->getMaskedEmail(); // Default masking (same as email_masked)
echo $user->getMaskedEmail(null, 3); // With max 3 asterisks
echo $user->getMaskedEmail('custom@example.com'); // Custom email with default masking
```

### As a utility function

You can also use the trait's static method directly:

```php
use Tchilly\EmailMasking\Traits\HasMaskedEmail;

$maskedEmail = HasMaskedEmail::maskEmail('john.doe@example.com');
echo $maskedEmail; // j******@example.com

// Customize the maximum number of asterisks
$maskedEmail = HasMaskedEmail::maskEmail('john.doe@example.com', 3);
echo $maskedEmail; // j***@example.com
```

## Configuration

You can customize the default masking behavior by overriding the `emailMasked` method in your model:

```php
// In your model
protected function emailMasked(): Attribute
{
    return new Attribute(
        get: fn () => static::maskEmail($this->email, 3) // Always use max 3 asterisks
    );
}
```

## Author

Magnus Vike <magnus@vike.se>

## License

This package is open-sourced software licensed under the MIT license.
