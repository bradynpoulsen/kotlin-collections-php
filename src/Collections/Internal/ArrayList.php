<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Collections\Internal;

use BradynPoulsen\Kotlin\Collections\Collection;
use BradynPoulsen\Kotlin\Collections\Common\ListArrayAccessTrait;
use BradynPoulsen\Kotlin\Collections\Common\ListIndexOfTrait;
use BradynPoulsen\Kotlin\Collections\ListOf;
use BradynPoulsen\Kotlin\Collections\MutableListOf;
use BradynPoulsen\Kotlin\NoSuchElementException;
use OutOfBoundsException;

/**
 * {@see ListOf} implementation backed by a PHP array.
 * @internal
 */
class ArrayList extends AbstractArrayCollection implements ListOf
{
    use ListIndexOfTrait;
    use ListArrayAccessTrait;

    /**
     * @param int $index
     * @return mixed
     * @see ListOf::get()
     */
    public function get(int $index)
    {
        if (!$this->containsIndex($index)) {
            throw new NoSuchElementException();
        }

        $element = $this->container[$index];

        assert(
            $this->getType()->containsValue($element),
            '$element must be a covariant value of ' . $this->getType()->getName()
        );
        return $element;
    }
}
