<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Common;

use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;
use function BradynPoulsen\Kotlin\Sequences\sequenceOf;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Common\SequenceCollectorTrait
 * @covers \BradynPoulsen\Kotlin\Sequences\Sequences
 */
class SequenceCollectorTraitTest extends TestCase
{
    /**
     * @test
     */
    public function toList_createsANewList(): void
    {
        self::assertEquals([1, 2, 3, 4], sequenceOf(Types::integer(), [1, 2, 3, 4])->toList()->toArray());
    }

    /**
     * @test
     */
    public function toSet_createANewUniqueSet(): void
    {
        self::assertEquals([1, 2, 3], sequenceOf(Types::integer(), [1, 1, 2, 2, 3, 3])->toSet()->toArray());
    }
}
