<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Common;

use BradynPoulsen\Kotlin\Collections\Map;
use BradynPoulsen\Kotlin\Collections\MutableMap;
use BradynPoulsen\Kotlin\InvalidArgumentException;
use BradynPoulsen\Kotlin\NoSuchElementException;
use BradynPoulsen\Kotlin\Pair;
use BradynPoulsen\Kotlin\Sequences\Internal\SequenceIteration;
use BradynPoulsen\Kotlin\Sequences\Sequence;
use BradynPoulsen\Kotlin\Types\Common\TypeAssurance;
use BradynPoulsen\Kotlin\Types\Type;
use BradynPoulsen\Kotlin\Types\Types;
use BradynPoulsen\Kotlin\UnsupportedOperationException;
use function BradynPoulsen\Kotlin\Collections\mutableListOf;
use function BradynPoulsen\Kotlin\Collections\mutableMapOf;
use function BradynPoulsen\Kotlin\pair;

trait SequenceTerminalOperationsTrait
{
    /**
     * @see Sequence::all()
     */
    public function all(callable $predicate): bool
    {
        assert($this instanceof Sequence);
        foreach ($this as $element) {
            if (!call_user_func($predicate, $element)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @see Sequence::any()
     */
    public function any(callable $predicate): bool
    {
        assert($this instanceof Sequence);
        foreach ($this as $element) {
            if (call_user_func($predicate, $element)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @see Sequence::associate()
     */
    public function associate(Type $keyType, Type $valueType, callable $transform): Map
    {
        assert($this instanceof Sequence);
        return $this->associateTo(mutableMapOf($keyType, $valueType), $transform)->toMap();
    }

    /**
     * @see Sequence::associateBy()
     */
    public function associateBy(Type $keyType, callable $keySelector): Map
    {
        assert($this instanceof Sequence);
        return $this->associateByTo(mutableMapOf($keyType, $this->getType()), $keySelector)->toMap();
    }

    /**
     * @see Sequence::associateTo()
     */
    public function associateTo(MutableMap $destination, callable $transform): MutableMap
    {
        assert($this instanceof Sequence);
        $pairType = Types::instance(Pair::class);
        foreach ($this as $index => $element) {
            $transformed = call_user_func($transform, $element);
            TypeAssurance::ensureContainedElementValue($pairType, '$transform function', $index, $transformed);
            assert($transformed instanceof Pair);
            $destination->put($transformed->getFirst(), $transformed->getSecond());
        }
        return $destination;
    }

    /**
     * @see Sequence::associateByTo()
     */
    public function associateByTo(MutableMap $destination, callable $keySelector): MutableMap
    {
        assert($this instanceof Sequence);
        foreach ($this as $index => $element) {
            $key = call_user_func($keySelector, $element);
            TypeAssurance::ensureContainedElementValue(
                $destination->getKeyType(),
                '$keySelector function',
                $index,
                $key
            );
            $destination->put($key, $element);
        }
        return $destination;
    }

    /**
     * @see Sequence::associateWith()
     */
    public function associateWith(Type $valueType, callable $valueSelector): Map
    {
        assert($this instanceof Sequence);
        return $this->associateWithTo(mutableMapOf($this->getType(), $valueType), $valueSelector)->toMap();
    }

    /**
     * @see Sequence::associateWithTo()
     */
    public function associateWithTo(MutableMap $destination, callable $valueSelector): MutableMap
    {
        assert($this instanceof Sequence);
        foreach ($this as $index => $element) {
            $value = call_user_func($valueSelector, $element);
            TypeAssurance::ensureContainedElementValue(
                $destination->getValueType(),
                '$valueSelector function',
                $index,
                $value
            );
            $destination->put($element, $value);
        }
        return $destination;
    }

    /**
     * @see Sequence::average()
     */
    public function average(): float
    {
        assert($this instanceof Sequence);
        TypeAssurance::ensureContainedArgumentType(Types::number(), 0, $this->getType(), Sequence::class);
        $count = 0.0;
        $total = 0.0;
        foreach ($this as $element) {
            $count += 1.0;
            $total += $element;
        }
        return $total / $count;
    }

    /**
     * @see Sequence::averageBy()
     */
    public function averageBy(callable $selector): float
    {
        assert($this instanceof Sequence);
        $numberType = Types::number();
        $count = 0.0;
        $total = 0.0;
        foreach ($this as $index => $element) {
            $count += 1.0;
            $selected = call_user_func($selector, $element);
            TypeAssurance::ensureContainedElementValue($numberType, 'selected value', $index, $selected);
            $total += $selected;
        }
        return $total / $count;
    }

    /**
     * @see Sequence::count()
     */
    public function count(): int
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        $count = 0;
        while ($iteration->hasNext()) {
            $iteration->next();
            $count++;
        }
        return $count;
    }

    /**
     * @see Sequence::countBy()
     */
    public function countBy(callable $predicate): int
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        $count = 0;
        while ($iteration->hasNext()) {
            if (call_user_func($predicate, $iteration->next())) {
                $count++;
            }
        }
        return $count;
    }

    /**
     * @see Sequence::first()
     */
    public function first()
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            throw new NoSuchElementException();
        }
        return $iteration->next();
    }

    /**
     * @see Sequence::firstBy()
     */
    public function firstBy(callable $predicate)
    {
        assert($this instanceof Sequence);
        foreach ($this as $element) {
            if (call_user_func($predicate, $element)) {
                return $element;
            }
        }
        throw new NoSuchElementException();
    }

    /**
     * @see Sequence::firstByOrNull()
     */
    public function firstByOrNull(callable $predicate)
    {
        assert($this instanceof Sequence);
        try {
            return $this->firstBy($predicate);
        } catch (NoSuchElementException $notFound) {
            return null;
        }
    }

    /**
     * @see Sequence::firstOrNull()
     */
    public function firstOrNull()
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            return null;
        }
        return $iteration->next();
    }

    /**
     * @see Sequence::fold()
     */
    public function fold($initial, callable $calculator)
    {
        assert($this instanceof Sequence);
        $accumulated = $initial;
        foreach ($this as $element) {
            $accumulated = call_user_func($calculator, $accumulated, $element);
        }
        return $accumulated;
    }

    /**
     * @see Sequence::foldIndexed()
     */
    public function foldIndexed($initial, callable $calculator)
    {
        assert($this instanceof Sequence);
        $accumulated = $initial;
        foreach ($this as $index => $element) {
            $accumulated = call_user_func($calculator, $accumulated, $index, $element);
        }
        return $accumulated;
    }

    /**
     * @see Sequence::isEmpty()
     */
    public function isEmpty(): bool
    {
        assert($this instanceof Sequence);
        return !$this->isNotEmpty();
    }

    /**
     * @see Sequence::isNotEmpty()
     */
    public function isNotEmpty(): bool
    {
        assert($this instanceof Sequence);
        return SequenceIteration::fromSequence($this)->hasNext();
    }

    /**
     * @see Sequence::joinToString()
     */
    public function joinToString(
        string $separator = ", ",
        string $prefix = "",
        string $postfix = "",
        int $limit = -1,
        string $truncated = "...",
        ?callable $transform = null
    ): string {
        assert($this instanceof Sequence);
        $result = $prefix;
        foreach ($this as $index => $element) {
            if ($index > 0) {
                $result .= $separator;
            }
            if ($limit > -1 && $index >= $limit) {
                $result .= $truncated;
                break;
            }
            $transformed = (null !== $transform) ? call_user_func($transform, $element) : $element;
            $result .= (string)$transformed;
        }
        $result .= $postfix;
        return $result;
    }

    /**
     * @see Sequence::last()
     */
    public function last()
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            throw new NoSuchElementException();
        }
        $last = $iteration->next();
        while ($iteration->hasNext()) {
            $last = $iteration->next();
        }
        return $last;
    }

    /**
     * @see Sequence::lastBy()
     */
    public function lastBy(callable $predicate)
    {
        assert($this instanceof Sequence);
        $last = null;
        $found = false;
        foreach ($this as $element) {
            if (call_user_func($predicate, $element)) {
                $last = $element;
                $found = true;
            }
        }
        if (!$found) {
            throw new NoSuchElementException();
        }
        return $last;
    }

    /**
     * @see Sequence::lastByOrNull()
     */
    public function lastByOrNull(callable $predicate)
    {
        assert($this instanceof Sequence);
        try {
            return $this->lastBy($predicate);
        } catch (NoSuchElementException $notFound) {
            return null;
        }
    }

    /**
     * @see Sequence::lastOrNull()
     */
    public function lastOrNull()
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            return null;
        }
        $last = $iteration->next();
        while ($iteration->hasNext()) {
            $last = $iteration->next();
        }
        return $last;
    }

    /**
     * @see Sequence::max()
     */
    public function max()
    {
        assert($this instanceof Sequence);
        TypeAssurance::ensureContainedArgumentType(Types::number(), 0, $this->getType(), Sequence::class);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            return null;
        }
        $min = $iteration->next();
        while ($iteration->hasNext()) {
            $element = $iteration->next();
            if ($element > $min) {
                $min = $element;
            }
        }
        return $min;
    }

    /**
     * @see Sequence::maxBy()
     */
    public function maxBy(callable $selector)
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            return null;
        }
        $maxElement = $iteration->next();
        $maxValue = call_user_func($selector, $maxElement);
        while ($iteration->hasNext()) {
            $element = $iteration->next();
            $value = call_user_func($selector, $element);
            if ($value > $maxValue) {
                $maxElement = $element;
                $maxValue = $value;
            }
        }
        return $maxElement;
    }

    /**
     * @see Sequence::maxWith()
     */
    public function maxWith(callable $comparator)
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            return null;
        }
        $max = $iteration->next();
        while ($iteration->hasNext()) {
            $element = $iteration->next();
            if (call_user_func($comparator, $max, $element) < 0) {
                $max = $element;
            }
        }
        return $max;
    }

    /**
     * @see Sequence::min()
     */
    public function min()
    {
        assert($this instanceof Sequence);
        TypeAssurance::ensureContainedArgumentType(Types::number(), 0, $this->getType(), Sequence::class);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            return null;
        }
        $min = $iteration->next();
        while ($iteration->hasNext()) {
            $element = $iteration->next();
            if ($element < $min) {
                $min = $element;
            }
        }
        return $min;
    }

    /**
     * @see Sequence::minBy()
     */
    public function minBy(callable $selector)
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            return null;
        }
        $minElement = $iteration->next();
        $minValue = call_user_func($selector, $minElement);
        while ($iteration->hasNext()) {
            $element = $iteration->next();
            $value = call_user_func($selector, $element);
            if ($value < $minValue) {
                $minElement = $element;
                $minValue = $value;
            }
        }
        return $minElement;
    }

    /**
     * @see Sequence::minWith()
     */
    public function minWith(callable $comparator)
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            return null;
        }
        $min = $iteration->next();
        while ($iteration->hasNext()) {
            $element = $iteration->next();
            if (call_user_func($comparator, $element, $min) < 0) {
                $min = $element;
            }
        }
        return $min;
    }

    /**
     * @see Sequence::none()
     */
    public function none(callable $predicate): bool
    {
        assert($this instanceof Sequence);
        foreach ($this as $element) {
            if (call_user_func($predicate, $element)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @see Sequence::partition()
     */
    public function partition(callable $predicate): Pair
    {
        assert($this instanceof Sequence);
        $matches = mutableListOf($this->getType());
        $nonMatches = mutableListOf($this->getType());
        foreach ($this as $element) {
            (call_user_func($predicate, $element) ? $matches : $nonMatches)
                ->add($element);
        }
        return pair($matches->toList(), $nonMatches->toList());
    }

    /**
     * @see Sequence::reduce()
     */
    public function reduce(callable $calculator)
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            throw new UnsupportedOperationException("Cannot reduce on empty sequence.");
        }
        $accumulated = $iteration->next();
        while ($iteration->hasNext()) {
            $accumulated = call_user_func($calculator, $accumulated, $iteration->next());
        }
        return $accumulated;
    }

    /**
     * @see Sequence::reduceIndexed()
     */
    public function reduceIndexed(callable $calculator)
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            throw new UnsupportedOperationException("Cannot reduce on empty sequence.");
        }
        $index = 0;
        $accumulated = $iteration->next();
        while ($iteration->hasNext()) {
            $accumulated = call_user_func($calculator, $accumulated, ++$index, $iteration->next());
        }
        return $accumulated;
    }

    /**
     * @see Sequence::single()
     */
    public function single()
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            throw new NoSuchElementException();
        }
        $value = $iteration->next();
        if ($iteration->hasNext()) {
            throw new InvalidArgumentException("More than one element found in sequence.");
        }
        return $value;
    }

    /**
     * @see Sequence::singleBy()
     */
    public function singleBy(callable $predicate)
    {
        assert($this instanceof Sequence);
        $found = false;
        $foundElement = null;
        foreach ($this as $element) {
            if (call_user_func($predicate, $element)) {
                if ($found) {
                    throw new InvalidArgumentException("More than one match found in sequence.");
                }
                $found = true;
                $foundElement = $element;
            }
        }
        if (!$found) {
            throw new NoSuchElementException();
        }
        return $foundElement;
    }

    /**
     * @see Sequence::singleByOrNull()
     */
    public function singleByOrNull(callable $predicate)
    {
        assert($this instanceof Sequence);
        $found = false;
        $foundElement = null;
        foreach ($this as $element) {
            if (call_user_func($predicate, $element)) {
                if ($found) {
                    return null;
                }
                $found = true;
                $foundElement = $element;
            }
        }
        return $foundElement;
    }

    /**
     * @see Sequence::singleOrNull()
     */
    public function singleOrNull()
    {
        assert($this instanceof Sequence);
        $iteration = SequenceIteration::fromSequence($this);
        if (!$iteration->hasNext()) {
            return null;
        }
        $value = $iteration->next();
        if ($iteration->hasNext()) {
            return null;
        }
        return $value;
    }

    /**
     * @see Sequence::sum()
     */
    public function sum(): int
    {
        assert($this instanceof Sequence);
        TypeAssurance::ensureContainedArgumentType(Types::integer(), 0, $this->getType(), Sequence::class);
        $total = 0;
        foreach ($this as $index => $element) {
            $total += $element;
        }
        return $total;
    }

    /**
     * @see Sequence::sumBy()
     */
    public function sumBy(callable $selector): int
    {
        assert($this instanceof Sequence);
        return $this->map(Types::integer(), $selector)->sum();
    }

    /**
     * @see Sequence::sumByFloat()
     */
    public function sumByFloat(callable $selector): float
    {
        assert($this instanceof Sequence);
        return $this->map(Types::float(), $selector)->sumFloat();
    }

    /**
     * @see Sequence::sumFloat()
     */
    public function sumFloat(): float
    {
        assert($this instanceof Sequence);
        TypeAssurance::ensureContainedArgumentType(Types::float(), 0, $this->getType(), Sequence::class);
        $total = 0.0;
        foreach ($this as $index => $element) {
            $total += $element;
        }
        return $total;
    }

    /**
     * @see Sequence::unzip()
     */
    public function unzip(Type $firstType, Type $secondType): Pair
    {
        assert($this instanceof Sequence);
        TypeAssurance::ensureContainedArgumentType(Types::instance(Pair::class), 0, $this->getType(), Sequence::class);
        $firstValues = mutableListOf($firstType);
        $secondValues = mutableListOf($secondType);
        foreach ($this as $element) {
            assert($element instanceof Pair);
            $firstValues->add($element->getFirst());
            $secondValues->add($element->getSecond());
        }
        return pair($firstValues, $secondValues);
    }
}
