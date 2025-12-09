<?php

namespace Theranken\Ectype\Traits\Enums;

use Theranken\Ectype\Attributes\Enums\Label;
use ReflectionEnumUnitCase;

trait HasLabel
{
    /**
     * Get the human-readable label for the enum case.
     * If a Label attribute is present, it returns its value.
     * Otherwise, it converts the case name to a human-readable format.
     */
    public function label(): string
    {
        $reflection = new ReflectionEnumUnitCase($this::class, $this->name);
        $attributes = $reflection->getAttributes(Label::class);

        if (count($attributes) > 0) {
            return $attributes[0]->newInstance()->label;
        }

        // Fallback: Convert PascalCase to readable string (e.g., 'OrderStatus' to 'Order Status')
        return ucwords(str_replace(['_', '-'], ' ', strtolower(preg_replace('/(?<!^)[A-Z]/', ' $0', $this->name))));
    }

    /**
     * Get the translated label for the enum case.
     * Uses Laravel's trans() helper for translation.
     *
     * @param string|null $locale The locale to use for translation.
     * @return string The translated label.
     */
    public function trans(?string $locale = null): string
    {
        $reflection = new ReflectionEnumUnitCase($this::class, $this->name);
        $attributes = $reflection->getAttributes(Label::class);

        if (count($attributes) > 0) {
            $labelAttribute = $attributes[0]->newInstance();
            if ($labelAttribute->isTranslationKey) {
                return trans($labelAttribute->label, [], $locale);
            }
            return $labelAttribute->label;
        }

        // Fallback: Generate a translation key from the enum class and value
        $translationKey = $this->generateTranslationKey();
        $translated = trans($translationKey, [], $locale);
        return $translated !== $translationKey ? $translated : $this->label();
    }

    /**
     * Generate a translation key from the enum class and case value/name.
     *
     * @return string
     */
    private function generateTranslationKey(): string
    {
        $reflection = new \ReflectionEnum($this::class);
        $value = $reflection->isBacked() ? $this->value : $this->name;
        $className = strtolower(str_replace(['App\\Enums\\', '\\'], ['', '.'], $this::class));
        return $className . '.' . strtolower($value);
    }
}
