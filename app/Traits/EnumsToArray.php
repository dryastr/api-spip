<?php

namespace App\Traits;

trait EnumsToArray
{
    /**
     * Convert enum values to array
     *
     * @return array
     */
    public static function toArray(): array
    {
        return array_map(
            fn (self $enum) => $enum->value,
            self::cases()
        );
    }

    /**
     * Convert enum values to validationRule
     *
     * @return array
     */
    public static function toValidation(): string
    {
        return 'in:'.implode(',', array_map(
            fn (self $enum) => $enum->value,
            self::cases()
        ));
    }
}
