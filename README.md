# Archetype - Supercharge Your Enums

A PHP library that provides powerful traits and attributes to enhance your enums with labels, collections, and Laravel translation support.

## Features

- **Labels**: Automatically generate human-readable labels or use custom labels via attributes.
- **Translation**: Built-in support for Laravel's translation system.
- **Collections**: Convenient methods to work with enum collections (names, values, select options).
- **Invokable**: Make enum cases callable to return their backing values.
- **Laravel Compatible**: Designed to work seamlessly with Laravel applications.

## Installation

Install via Composer:

```bash
composer require theranken/archetype
```

## Requirements

- PHP 8.1 or higher
- Laravel 9+ (for translation features)

## Usage

### Basic Enum with Labels

```php
<?php

namespace App\Enums;

use Theranken\Archetype\Traits\Enums\HasLabel;

enum Status: string
{
    use HasLabel;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

// Usage
$status = Status::PENDING;
echo $status->label(); // "Pending"
```

### Custom Labels

```php
<?php

namespace App\Enums;

use Theranken\Archetype\Traits\Enums\HasLabel;
use Theranken\Archetype\Attributes\Enums\Label;

enum Status: string
{
    use HasLabel;

    #[Label('Awaiting Review')]
    case PENDING = 'pending';

    #[Label('Approved')]
    case APPROVED = 'approved';

    #[Label('Rejected')]
    case REJECTED = 'rejected';
}

// Usage
echo Status::PENDING->label(); // "Awaiting Review"
```

### Translation Support

```php
<?php

namespace App\Enums;

use Theranken\Archetype\Traits\Enums\HasLabel;
use Theranken\Archetype\Attributes\Enums\Label;

enum Status: string
{
    use HasLabel;

    #[Label('status.pending', isTranslationKey: true)]
    case PENDING = 'pending';

    #[Label('status.approved', isTranslationKey: true)]
    case APPROVED = 'approved';
}

// Usage
echo Status::PENDING->trans(); // Translates using Laravel's trans() helper
echo Status::PENDING->trans('es'); // Translate to Spanish
```

### Enum Collections

```php
<?php

namespace App\Enums;

use Theranken\Archetype\Traits\Enums\HasLabel;
use Theranken\Archetype\Traits\Enums\HasEnumCollections;

enum Status: string
{
    use HasLabel, HasEnumCollections;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

// Usage
Status::names(); // ['PENDING', 'APPROVED', 'REJECTED']
Status::values(); // ['pending', 'approved', 'rejected']
Status::nameValues(); // ['PENDING' => 'pending', ...]
Status::toSelectArray(); // ['pending' => 'Pending', 'approved' => 'Approved', ...]
Status::toOptionsArray(); // [['name' => 'PENDING', 'value' => 'pending', 'label' => 'Pending'], ...]
```

### Invokable Enums

```php
<?php

namespace App\Enums;

use Theranken\Archetype\Traits\Enums\Invokable;

enum Status: string
{
    use Invokable;

    case PENDING = 'pending';
    case APPROVED = 'approved';
}

// Usage
$status = Status::PENDING;
echo $status(); // 'pending'
```

## API Reference

### Traits

#### HasLabel

Provides methods for working with enum labels and translations.

- `label(): string` - Get the human-readable label for the enum case.
- `trans(?string $locale = null): string` - Get the translated label using Laravel's translation system.

#### HasEnumCollections

Provides static methods for working with enum collections.

- `names(): array<string>` - Get all enum case names.
- `values(): array<scalar>` - Get all enum case backing values.
- `nameValues(): array<string, scalar>` - Get associative array of names => values.
- `valueNames(): array<scalar, string>` - Get associative array of values => names.
- `toSelectArray(): array<scalar, string>` - Get options for select inputs (requires HasLabel).
- `toOptionsArray(): array<array<string, mixed>>` - Get detailed options array.

#### Invokable

Makes enum cases callable to return their backing value.

- `__invoke(): mixed` - Returns the backing value of the enum case.

### Attributes

#### Label

Define custom labels for enum cases.

```php
#[Label(string $label, bool $isTranslationKey = false)]
```

- `$label`: The label text or translation key.
- `$isTranslationKey`: Whether the label is a translation key (default: false).

## Laravel Integration

### Translation Files

Create translation files in `resources/lang/`:

```php
// resources/lang/en/status.php
return [
    'pending' => 'Pending',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
];

// resources/lang/es/status.php
return [
    'pending' => 'Pendiente',
    'approved' => 'Aprobado',
    'rejected' => 'Rechazado',
];
```

### Automatic Translation Keys

If no `Label` attribute is provided, the library generates translation keys based on the enum class and value:

- `App\Enums\Status::PENDING` becomes `status.pending`
- `App\Enums\Order\Status::PENDING` becomes `order.status.pending`

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
