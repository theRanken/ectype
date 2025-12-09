<?php

namespace Ranken\Ectype\Attributes\Enums;

use Attribute;

/**
 * Attribute to define a custom label for an enum case.
 * The label can be a literal string or a translation key.
 */
#[Attribute(Attribute::TARGET_CLASS_CONSTANT)]
class Label
{
    /**
     * @param string $label The label text or translation key.
     * @param bool $isTranslationKey Whether the label is a translation key (default: false).
     */
    public function __construct(
        public string $label,
        public bool $isTranslationKey = false
    ) {}
}
