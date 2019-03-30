<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\Types\Type;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers \BradynPoulsen\Kotlin\Types\Internal\TypeAssuranceTrait
 * @covers \BradynPoulsen\Kotlin\Types\Internal\TypeVariance
 */
class TypeAssuranceTraitTest extends TestCase
{
    /**
     * @var Type
     */
    private static $alwaysInvalidType;

    /**
     * @var Type
     */
    private static $alwaysValidType;

    /**
     * @test
     */
    public function invariantValueInvalid(): void
    {
        try {
            self::$alwaysInvalidType->ensureInvariantValue(1, null);
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
        self::$alwaysValidType->ensureInvariantValue(1, null);
        self::assertNull(null);
    }

    /**
     * @test
     */
    public function covariantValueInvalid(): void
    {
        try {
            self::$alwaysInvalidType->ensureCovariantValue(1, null);
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
        self::$alwaysValidType->ensureCovariantValue(1, null);
        self::assertNull(null);
    }

    /**
     * @test
     */
    public function contravariantValueInvalid(): void
    {
        try {
            self::$alwaysInvalidType->ensureContravariantValue(1, null);
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
        self::$alwaysValidType->ensureContravariantValue(1, null);
        self::assertNull(null);
    }

    /**
     * @test
     */
    public function invariantTypeInvalid(): void
    {
        try {
            self::$alwaysInvalidType->ensureInvariantType(1, self::$alwaysInvalidType);
        } catch (TypeError $error) {
            $message = $error->getMessage();
            self::assertStringStartsWith("Argument 1 passed to " . __METHOD__ . "() must be of type nothing, nothing given", $message);
            return;
        }

        self::fail("TypeError was not caught!");
    }

    /**
     * @test
     */
    public function invariantTypeValid(): void
    {
        self::$alwaysValidType->ensureInvariantType(1, self::$alwaysValidType);
        self::assertNull(null);
    }

    /**
     * @test
     */
    public function covariantTypeInvalid(): void
    {
        try {
            self::$alwaysInvalidType->ensureCovariantType(1, self::$alwaysInvalidType);
        } catch (TypeError $error) {
            $message = $error->getMessage();
            self::assertStringStartsWith("Argument 1 passed to " . __METHOD__ . "() must be of covariant type nothing, nothing given", $message);
            return;
        }

        self::fail("TypeError was not caught!");
    }

    /**
     * @test
     */
    public function covariantTypeValid(): void
    {
        self::$alwaysValidType->ensureCovariantType(1, self::$alwaysValidType);
        self::assertNull(null);
    }

    /**
     * @test
     */
    public function contravariantTypeInvalid(): void
    {
        try {
            self::$alwaysInvalidType->ensureContravariantType(1, self::$alwaysInvalidType);
        } catch (TypeError $error) {
            $message = $error->getMessage();
            self::assertStringStartsWith("Argument 1 passed to " . __METHOD__ . "() must be of contravariant type nothing, nothing given", $message);
            return;
        }

        self::fail("TypeError was not caught!");
    }

    /**
     * @test
     */
    public function contravariantTypeValid(): void
    {
        self::$alwaysValidType->ensureContravariantType(1, self::$alwaysValidType);
        self::assertNull(null);
    }

    /**
     * @beforeClass
     */
    public static function createAlwaysInvalidType()
    {
        self::$alwaysInvalidType = new class implements Type {
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
            public function isCovariantType(Type $type): bool
            {
                return false;
            }
            public function isContravariantType(Type $type): bool
            {
                return false;
            }
            public function isInvariantType(Type $type): bool
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
        self::$alwaysValidType = new class implements Type {
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
            public function isCovariantType(Type $type): bool
            {
                return true;
            }
            public function isContravariantType(Type $type): bool
            {
                return true;
            }
            public function isInvariantType(Type $type): bool
            {
                return true;
            }
        };
    }
}
