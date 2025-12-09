<?php

namespace Theranken\Ectype\Traits\Enums;

use Theranken\Ectype\Traits\Enums\HasLabel;

trait HasEnumCollections
{
    /**
     * Get an array of all enum case names.
     *
     * @return array<string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * Check if the enum is backed (has scalar values).
     *
     * @return bool
     */
    private static function isBacked(): bool
    {
        $reflection = new \ReflectionEnum(self::class);
        return $reflection->isBacked();
    }

    /**
     * Get an array of all enum case values.
     * For backed enums, returns backing values; for pure enums, returns case names.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        if (self::isBacked()) {
            return array_column(self::cases(), 'value');
        }
        return self::names();
    }

    /**
     * Get an associative array of enum names => values.
     * For backed enums, values are backing values; for pure enums, values are names.
     *
     * @return array<string, string>
     */
    public static function nameValues(): array
    {
        return array_combine(self::names(), self::values());
    }

    /**
     * Get an associative array of enum values => names.
     * For backed enums, values are backing values; for pure enums, values are names.
     *
     * @return array<string, string>
     */
    public static function valueNames(): array
    {
        return array_combine(self::values(), self::names());
    }

    /**
     * Get an array of options suitable for select inputs,
     * typically `value` => `label`.
     * This assumes the enum uses the `HasLabel` trait.
     *
     * @return array<string, string>
     */
    public static function toSelectArray(): array
    {
        if (! in_array(HasLabel::class, class_uses_recursive(self::class))) {
            throw new \LogicException('The toSelectArray method requires the HasLabel trait to be present on the enum.');
        }

        $options = [];
        foreach (self::cases() as $case) {
            $value = self::isBacked() ? $case->value : $case->name;
            $options[$value] = $case->label();
        }
        return $options;
    }

    /**
     * Get an array of options as objects, for more complex scenarios.
     *
     * @return array<array<string, mixed>>
     */
    public static function toOptionsArray(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $value = self::isBacked() ? $case->value : $case->name;
            $option = [
                'name' => $case->name,
                'value' => $value,
            ];

            // Conditionally add label if the HasLabel trait is used
            if (in_array(HasLabel::class, class_uses_recursive(self::class))) {
                $option['label'] = $case->label();
            }

            $options[] = $option;
        }
        return $options;
    }
}
