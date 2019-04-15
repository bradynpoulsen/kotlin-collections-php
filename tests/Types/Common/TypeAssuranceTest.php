<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Common;

use BradynPoulsen\Kotlin\Types\Type;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers \BradynPoulsen\Kotlin\Types\Common\TypeAssurance
 */
class TypeAssuranceTest extends TestCase
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
    public function valueInvalid(): void
    {
        try {
            TypeAssurance::ensureContainedValue(self::$alwaysInvalidType, 1, null);
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
    public function valueValid(): void
    {
        $this->expectNotToPerformAssertions();
        TypeAssurance::ensureContainedValue(self::$alwaysValidType, 1, null);
    }

    /**
     * @test
     */
    public function typeInvalid(): void
    {
        try {
            TypeAssurance::ensureContainedType(self::$alwaysInvalidType, 1, self::$alwaysInvalidType);
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
    public function typeValid(): void
    {
        $this->expectNotToPerformAssertions();
        TypeAssurance::ensureContainedType(self::$alwaysValidType, 1, self::$alwaysValidType);
    }

    /**
     * @beforeClass
     */
    public static function createAlwaysInvalidType()
    {
        self::$alwaysInvalidType = new class implements Type {
            public function getName(): string
            {
                return 'nothing';
            }
            public function isScalar(): bool
            {
                return false;
            }
            public function isCompound(): bool
            {
                return false;
            }
            public function acceptsDynamicArray(): bool
            {
                return false;
            }
            public function acceptsNull(): bool
            {
                return false;
            }
            public function containsValue($value): bool
            {
                return false;
            }
            public function containsType(Type $type): bool
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
            public function getName(): string
            {
                return 'anything';
            }
            public function isScalar(): bool
            {
                return false;
            }
            public function isCompound(): bool
            {
                return false;
            }
            public function acceptsDynamicArray(): bool
            {
                return true;
            }
            public function acceptsNull(): bool
            {
                return true;
            }
            public function containsValue($value): bool
            {
                return true;
            }
            public function containsType(Type $type): bool
            {
                return true;
            }
        };
    }
}
