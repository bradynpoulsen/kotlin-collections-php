<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Types\Types;
use PHPUnit\Framework\TestCase;

/**
 * @covers \BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractIteration
 */
class AbstractIterationTest extends TestCase
{
    /**
     * @test
     */
    public function hasNext_onFailedIteration(): void
    {
        $this->expectException(InvalidStateException::class);

        $type = Types::nothing();
        $iteration = new class($type) extends AbstractIteration {
            protected function computeNext(): void
            {
            }
        };

        // verify simple type storing
        self::assertSame($type, $iteration->getType());

        self::assertFalse($iteration->hasNext());
        $iteration->hasNext();
    }

    /**
     * @test
     */
    public function hasNext_onDone(): void
    {
        $iteration = new class(Types::nothing()) extends AbstractIteration {
            private $called = false;

            protected function computeNext(): void
            {
                if ($this->called) {
                    throw new InvalidStateException("Cannot call computeNext once it has already completed");
                }

                $this->called = true;
                $this->done();
            }
        };

        // calling hasNext does not compute multiple times
        self::assertFalse($iteration->hasNext());
        self::assertFalse($iteration->hasNext());
    }

    /**
     * @test
     */
    public function next_afterCompleted(): void
    {
        $iteration = new class(Types::nothing()) extends AbstractIteration {
            private $called = false;

            protected function computeNext(): void
            {
                if ($this->called) {
                    throw new InvalidStateException("Cannot call computeNext once it has already completed");
                }

                $this->called = true;
                $this->done();
            }
        };

        $this->expectException(NoSuchElementException::class);
        $iteration->next();
    }
}
