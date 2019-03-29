<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types;

use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers \BradynPoulsen\Kotlin\Types\TypeAssuranceTrait
 * @covers \BradynPoulsen\Kotlin\Types\TypeVariance
 */
class TypeAssuranceTraitTest extends TestCase
{
    /**
     * @var Type
     */
    private static $alwaysInvalidValue;

    /**
     * @var Type
     */
    private static $alwaysValidValue;

    /**
     * @test
     */
    public function invariantTypeError(): void
    {
        try {
            self::$alwaysInvalidValue->ensureInvariantValue(1, null);
        } catch (TypeError $error) {
            $message = $error->getMessage();
            self::assertStringStartsWith("Argument 1 passed to " . __METHOD__ . "() must be of type nothing, null given", $message);
            return;
        }

        self::fail("TypeError was not caught!");
    }

    /**
     * @test
     */
    public function invariantValueValid(): void
    {
        self::$alwaysValidValue->ensureInvariantValue(1, null);
        self::assertNull(null);
    }

    /**
     * @test
     */
    public function covariantTypeError(): void
    {
        try {
            self::$alwaysInvalidValue->ensureCovariantValue(1, null);
        } catch (TypeError $error) {
            $message = $error->getMessage();
            self::assertStringStartsWith("Argument 1 passed to " . __METHOD__ . "() must be of covariant type nothing, null given", $message);
            return;
        }

        self::fail("TypeError was not caught!");
    }

    /**
     * @test
     */
    public function covariantValueValid(): void
    {
        self::$alwaysValidValue->ensureCovariantValue(1, null);
        self::assertNull(null);
    }

    /**
     * @test
     */
    public function contravariantTypeError(): void
    {
        try {
            self::$alwaysInvalidValue->ensureContravariantValue(1, null);
        } catch (TypeError $error) {
            $message = $error->getMessage();
            self::assertStringStartsWith("Argument 1 passed to " . __METHOD__ . "() must be of contravariant type nothing, null given", $message);
            return;
        }

        self::fail("TypeError was not caught!");
    }

    /**
     * @test
     */
    public function contravariantValueValid(): void
    {
        self::$alwaysValidValue->ensureContravariantValue(1, null);
        self::assertNull(null);
    }

    /**
     * @beforeClass
     */
    public static function createAlwaysInvalidType()
    {
        self::$alwaysInvalidValue = new class implements Type {
            use TypeAssuranceTrait;
            public function getName(): string
            {
                return 'nothing';
            }
            public function isScalar(): bool
            {
                return false;
            }
            public function isPseudo(): bool
            {
                return false;
            }
            public function isNullable(): bool
            {
                return false;
            }
            public function isCovariantValue($value): bool
            {
                return false;
            }
            public function isContravariantValue($value): bool
            {
                return false;
            }
            public function isInvariantValue($value): bool
            {
                return false;
            }
        };
    }

    /**
     * @beforeClass
     */
    public static function createAlwaysValidType()
    {
        self::$alwaysValidValue = new class implements Type {
            use TypeAssuranceTrait;
            public function getName(): string
            {
                return 'anything';
            }
            public function isScalar(): bool
            {
                return false;
            }
            public function isPseudo(): bool
            {
                return false;
            }
            public function isNullable(): bool
            {
                return true;
            }
            public function isCovariantValue($value): bool
            {
                return true;
            }
            public function isContravariantValue($value): bool
            {
                return true;
            }
            public function isInvariantValue($value): bool
            {
                return true;
            }
        };
    }
}
