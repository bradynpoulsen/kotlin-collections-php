<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate;

use BradynPoulsen\Kotlin\Collections\Internal\MutableArrayList;
use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\Collections\MutableListOf;
use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\InvalidStateException;
use BradynPoulsen\Kotlin\Sequences\Internal\Base\IteratorIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\EmptyIteration;
use BradynPoulsen\Kotlin\Sequences\Internal\TypedIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Types;
use Generator;

/**
 * @internal
 */
final class WindowedSequence extends AbstractLinkedIterationSequence
{
    /**
     * @var int
     */
    private $size;
    /**
     * @var int
     */
    private $step;
    /**
     * @var bool
     */
    private $partialWindows;

    public function __construct(Sequence $previous, int $size, int $step, bool $partialWindow)
    {
        parent::__construct($previous, Types::instance(ListOf::class));
        if ($size <= 0 || $step <= 0) {
            throw new InvalidArgumentException("size $size and step $step must be greater than zero.");
        }
        $this->size = $size;
        $this->step = $step;
        $this->partialWindows = $partialWindow;
    }

    public function getIteration(): TypedIteration
    {
        $operation = new DisposableSequenceOperation($this);
        if (!$operation->getIteration()->hasNext()) {
            $operation->dispose();
            return new EmptyIteration();
        }
        return new IteratorIteration($this->getType(), $this->createGenerator($operation));
    }

    private function createGenerator(DisposableSequenceOperation $operation): Generator
    {
        $gap = $this->step - $this->size;
        if ($gap >= 0) {
            $buffer = new MutableArrayList($operation->getType());
            $skip = 0;
            $iteration = $operation->getIteration();
            while ($iteration->hasNext()) {
                $element = $iteration->next();
                if ($skip > 0) {
                    $skip -= 1;
                    continue;
                }
                $buffer->add($element);
                if (count($buffer) === $this->size) {
                    yield $buffer->toList();
                    $buffer->clear();
                    $skip = $gap;
                }
            }
            $operation->dispose();
            if (!$buffer->isEmpty()) {
                if ($this->partialWindows || count($buffer) === $this->size) {
                    yield $buffer->toList();
                }
            }
        } else {
            $buffer = new MutableArrayList($operation->getType());
            $iteration = $operation->getIteration();
            while ($iteration->hasNext()) {
                $element = $iteration->next();
                $buffer->add($element);
                if (count($buffer) === $this->size) {
                    yield $buffer->toList();
                    $this->removeFirst($buffer, $this->step);
                }
            }
            $operation->dispose();
            if ($this->partialWindows) {
                while (count($buffer) > $this->step) {
                    yield $buffer->toList();
                    $this->removeFirst($buffer, $this->step);
                }
                if (!$buffer->isEmpty()) {
                    yield $buffer->toList();
                }
            }
        }
    }

    private function removeFirst(MutableListOf $listOf, int $count)
    {
        // @codeCoverageIgnoreStart
        if ($count < 0) {
            throw new InvalidArgumentException("count $count must not be negative");
        }
        if (count($listOf) < $count) {
            throw new InvalidStateException(
                "count $count cannot be larger than the buffer size " . count($listOf)
            );
        }
        // @codeCoverageIgnoreEnd

        if ($count > 0) {
            $remaining = $count;
            while ($remaining > 0) {
                $listOf->removeAt(0);
                $remaining -= 1;
            }
        }
    }
}
