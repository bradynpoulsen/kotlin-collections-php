<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Sequences\Internal\Operations\Intermediate\AbstractIteration;
use BradynPoulsen\Kotlin\Types\Type;

/**
 * @internal
 */
final class ReverseArrayIteration extends AbstractIteration
{
    /**
     * @var array
     */
    private $elements;

    /**
     * @var int
     */
    private $lastIndex = -1;

    public function __construct(Type $type, array $elements)
    {
        parent::__construct($type);
        if (empty($elements)) {
            $this->done();
            return;
        }

        $this->elements = $elements;
        $this->lastIndex = count($elements) - 1;
    }

    protected function computeNext(): void
    {
        if ($this->getNextPosition() <= $this->lastIndex) {
            $this->setNext($this->elements[$this->lastIndex - $this->getNextPosition()]);
            return;
        }

        $this->done();
    }
}
