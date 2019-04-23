<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Sequences\Internal;

use BradynPoulsen\Kotlin\Sequences\Sequence;

/**
 * @internal
 */
interface DropTakeSequence extends Sequence
{
    /**
     * @see Sequence::drop()
     */
    public function drop(int $count): Sequence;

    /**
     * @see Sequence::take()
     */
    public function take(int $count): Sequence;
}
