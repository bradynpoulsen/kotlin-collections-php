<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin;

function pair($first, $second): Pair
{
    return new Pair($first, $second);
}
