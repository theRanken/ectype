# Ectype - Supercharge Your Enums

A PHP library that provides powerful traits and attributes to enhance your enums with labels, collections, and Laravel translation support.

## Features

- **Labels**: Automatically generate human-readable labels or use custom labels via attributes.
- **Translation**: Built-in support for Laravel's translation system (Laravel projects only).
- **Collections**: Convenient methods to work with enum collections (names, values, select options).
- **Invokable**: Make enum cases callable to return their backing values.
- **Framework Agnostic**: Works in any PHP 8.1+ project, with optional Laravel integration.

## Installation

Install via Composer:

```bash
composer require theranken/ectype
```

## Requirements

- PHP 8.1 or higher
- Laravel 9+ (for translation features)

## Usage

### Basic Enum with Labels

```php
<?php

namespace App\Enums;

use Theranken\Ectype\Traits\Enums\HasLabel;

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

use Theranken\Ectype\Traits\Enums\HasLabel;
use Theranken\Ectype\Attributes\Enums\Label;

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

use Theranken\Ectype\Traits\Enums\HasLabel;
use Theranken\Ectype\Attributes\Enums\Label;

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

#### Backed Enum

```php
<?php

namespace App\Enums;

use Theranken\Ectype\Traits\Enums\HasLabel;
use Theranken\Ectype\Traits\Enums\HasEnumCollections;

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

#### Pure (Non-Backed) Enum

```php
<?php

namespace App\Enums;

use Theranken\Ectype\Traits\Enums\HasLabel;
use Theranken\Ectype\Traits\Enums\HasEnumCollections;

enum Priority
{
    use HasLabel, HasEnumCollections;

    case LOW;
    case MEDIUM;
    case HIGH;
}

// Usage
Priority::names(); // ['LOW', 'MEDIUM', 'HIGH']
Priority::values(); // ['LOW', 'MEDIUM', 'HIGH'] (same as names for pure enums)
Priority::nameValues(); // ['LOW' => 'LOW', 'MEDIUM' => 'MEDIUM', ...]
Priority::toSelectArray(); // ['LOW' => 'Low', 'MEDIUM' => 'Medium', ...]
Priority::toOptionsArray(); // [['name' => 'LOW', 'value' => 'LOW', 'label' => 'Low'], ...]
```

### Invokable Enums

```php
<?php

namespace App\Enums;

use Theranken\Ectype\Traits\Enums\Invokable;

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

## Non-Laravel Usage

The library can be used in any PHP 8.1+ project, not just Laravel applications. All features work except for the `trans()` method in the `HasLabel` trait, which depends on Laravel's translation system.

### Core Features That Work Without Laravel

- **HasLabel**: The `label()` method works for generating human-readable labels from enum names or custom `Label` attributes.
- **HasEnumCollections**: All collection methods work (names, values, arrays, etc.).
- **Invokable**: Makes enum cases callable to return their values.
- **Label Attribute**: Works for custom labels.

### Limitations

The `trans()` method in `HasLabel` won't work because it depends on Laravel's `trans()` helper function.

### Adapted Usage Examples

```php
<?php

namespace YourNamespace\Enums;

use Theranken\Ectype\Traits\Enums\HasLabel;
use Theranken\Ectype\Traits\Enums\HasEnumCollections;
use Theranken\Ectype\Attributes\Enums\Label;

enum Status: string
{
    use HasLabel, HasEnumCollections;

    #[Label('Awaiting Review')]
    case PENDING = 'pending';

    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}

// These work perfectly:
echo Status::PENDING->label(); // "Awaiting Review"
echo Status::APPROVED->label(); // "Approved" (auto-generated)

$names = Status::names(); // ['PENDING', 'APPROVED', 'REJECTED']
$values = Status::values(); // ['pending', 'approved', 'rejected']
$selectOptions = Status::toSelectArray(); // ['pending' => 'Awaiting Review', ...]
```

### Handling Translations Without Laravel

For translation support in non-Laravel projects, you'll need to implement your own translation system. Here are two approaches:

#### Option 1: Custom Translation Trait

Create your own translation trait that works with your preferred i18n library:

```php
<?php

trait HasTranslations
{
    public function trans(?string $locale = null): string
    {
        // Implement using your translation library
        // Example with a simple array-based system:
        $translations = [
            'en' => ['pending' => 'Pending', 'approved' => 'Approved'],
            'es' => ['pending' => 'Pendiente', 'approved' => 'Aprobado'],
        ];

        $key = strtolower($this->value ?? $this->name);
        return $translations[$locale ?? 'en'][$key] ?? $this->label();
    }
}
```

#### Option 2: Skip Translation Features

Simply don't use the `trans()` method and rely on the `label()` method for display text. This works well for applications that don't need internationalization.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
