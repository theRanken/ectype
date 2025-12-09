<?php

namespace Theranken\Archetype\Traits\Enums;

use Theranken\Archetype\Traits\Enums\HasLabel;

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
     * Get an array of all enum case backing values.
     * This method is only applicable to Backed Enums.
     *
     * @return array<scalar>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get an associative array of enum names => backing values.
     *
     * @return array<string, scalar>
     */
    public static function nameValues(): array
    {
        return array_combine(self::names(), self::values());
    }

    /**
     * Get an associative array of enum backing values => names.
     *
     * @return array<scalar, string>
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
     * @return array<scalar, string>
     */
    public static function toSelectArray(): array
    {
        if (! in_array(HasLabel::class, class_uses_recursive(self::class))) {
            throw new \LogicException('The HasSelectOptions trait requires the HasLabel trait to be present on the enum.');
        }

        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
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
            $option = [
                'name' => $case->name,
                'value' => $case->value,
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