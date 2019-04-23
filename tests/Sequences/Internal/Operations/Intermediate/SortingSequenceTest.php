<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\SortingSequence
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\ReverseArrayIteration
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceIntermediateOperationsTrait
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorIteration
 */
class SortingSequenceTest extends TestCase
{
    /**
     * @test
     */
    public function sorted_usingCoreSort(): void
    {
        self::assertEquals(
            ["1", "2", "3", "4", "5", "6"],
            sequenceOf(Types::string(), ["1", "6", "4", "2", "5", "3"])
                ->sorted()
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function sortedDescending_usingCoreSort(): void
    {
        self::assertEquals(
            ["6", "5", "4", "3", "2", "1"],
            sequenceOf(Types::string(), ["1", "6", "4", "2", "5", "3"])
                ->sortedDescending()
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function sortedBy_pluckedPropertyFromObject(): void
    {
        $one = $this->objectWithProperty(1);
        $two = $this->objectWithProperty(2);
        $three = $this->objectWithProperty(3);

        self::assertSame(
            [$one, $two, $two, $three],
            sequenceOf(Types::object(), [$two, $three, $two, $one])
                ->sortedBy(function (object $o): int {
                    return $o->value;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function sortedByDescending_pluckedPropertyFromObject(): void
    {
        $one = $this->objectWithProperty(1);
        $two = $this->objectWithProperty(2);
        $three = $this->objectWithProperty(3);

        self::assertSame(
            [$three, $two, $one],
            sequenceOf(Types::object(), [$two, $three, $one])
                ->sortedByDescending(function (object $o): int {
                    return $o->value;
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function sortedWith_customComparator(): void
    {
        $january = $this->objectWithProperty(new \DateTime('2019-01-15'));
        $february = $this->objectWithProperty(new \DateTime('2019-02-06'));
        $march = $this->objectWithProperty(new \DateTime('2019-03-30'));

        self::assertSame(
            [$february, $january, $march],
            sequenceOf(Types::object(), [$march, $january, $february])
                ->sortedWith(function ($a, $b): int {
                    return ((int)$a->value->format('d')) - ((int)$b->value->format('d'));
                })
                ->toArray()
        );
    }

    /**
     * @test
     */
    public function sortedWithDescending_customComparator(): void
    {
        $january = $this->objectWithProperty(new \DateTime('2019-01-15'));
        $february = $this->objectWithProperty(new \DateTime('2019-02-06'));
        $march = $this->objectWithProperty(new \DateTime('2019-03-30'));

        self::assertSame(
            [$march, $january, $february],
            sequenceOf(Types::object(), [$january, $march, $february])
                ->sortedWithDescending(function ($a, $b): int {
                    return ((int)$a->value->format('d')) - ((int)$b->value->format('d'));
                })
                ->toArray()
        );
    }

    private function objectWithProperty($value): object
    {
        return new class($value) {
            public $value;

            public function __construct($value)
            {
                $this->value = $value;
            }
        };
    }
}
