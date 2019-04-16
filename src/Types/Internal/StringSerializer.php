<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

final class StringSerializer
{
    private function __construct()
    {
    }

    public static function prepareValue($value): string
    {
        if (is_null($value)) {
            return 'null';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string)$value;
    }
}
