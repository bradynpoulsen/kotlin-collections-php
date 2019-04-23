<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Base;

use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use function BradynPoulsen\Kotlin\Sequences\sequence;
use BradynPoulsen\Kotlin\Types\Types;
use EmptyIterator;
use Generator;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorFactorySequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Sequences
 * @covers ::\BradynPoulsen\Kotlin\Sequences\sequence()
 */
class IteratorFactorySequenceTest extends TestCase
{
    /**
     * @test
     */
    public function basedOnAGenerator(): void
    {
        $sequence = sequence(
            Types::integer(),
            function (): Generator {
                yield 1;
                yield 2;
                yield 3;
                yield 4;
            }
        );

        self::assertEquals([1, 2, 3, 4], $sequence->toArray());
    }

    /**
     * @test
     */
    public function basedOnTypeCheckFactory(): void
    {
        self::assertEmpty(
            sequence(
                Types::integer(),
                function (): TypeCheckIterator {
                    return new TypeCheckIterator(Types::nothing(), new EmptyIterator());
                }
            )->toArray()
        );
    }

    /**
     * @test
     */
    public function closureFactory_failsIfNotIterator(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessageRegExp(
            "/^Iterator returned by factory Closure#\d+ must be of type Iterator, integer given$/"
        );
        sequence(
            Types::nothing(),
            function (): int {
                return 0;
            }
        )->toArray();
    }

    /**
     * @test
     */
    public function instanceArrayFactory_failsIfNotIterator(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessageRegExp(
            '/factory BradynPoulsen\\\\Kotlin\\\\Sequences\\\\Internal\\\\Base\\\\IteratorFactorySequenceTest#\d+\-\>badArrayFactory\(\)/'
        );
        sequence(
            Types::nothing(),
            [$this, 'badArrayFactory']
        )->toArray();
    }

    /**
     * @test
     */
    public function staticArrayFactory_failsIfNotIterator(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessageRegExp(
            '/factory BradynPoulsen\\\\Kotlin\\\\Sequences\\\\Internal\\\\Base\\\\IteratorFactorySequenceTest::badStringFactory\(\)/'
        );
        sequence(
            Types::nothing(),
            [self::class, 'badStringFactory']
        )->toArray();
    }

    /**
     * @test
     */
    public function stringFactory_failsIfNotIterator(): void
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessageRegExp(
            '/factory BradynPoulsen\\\\Kotlin\\\\Sequences\\\\Internal\\\\Base\\\\IteratorFactorySequenceTest::badStringFactory\(\)/'
        );
        sequence(
            Types::nothing(),
            sprintf('%s::%s', self::class, 'badStringFactory')
        )->toArray();
    }

    public function badArrayFactory(): int
    {
        return 1;
    }

    public static function badStringFactory(): int
    {
        return 1;
    }
}
