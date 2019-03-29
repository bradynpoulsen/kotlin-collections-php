<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types;

use BadMethodCallException;
use InvalidArgumentException;

/**
 * Used by {@see TypeAssuranceTrait} to relay intended variance checking.
 * @internal
 */
class TypeVariance
{
    public const INVARIANT = 1;
    public const COVARIANT = 2;
    public const CONTRAVARIANT = 3;

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
        throw new BadMethodCallException("Cannot create instance of static utilities class");
    }

    public static function toVarianceName(int $value): string
    {
        switch ($value) {
            case self::INVARIANT: return 'type';
            case self::COVARIANT: return 'covariant type';
            case self::CONTRAVARIANT: return 'contravariant type';
            default: throw new InvalidArgumentException("Unknown value for variance: $value");
        }
    }
}
