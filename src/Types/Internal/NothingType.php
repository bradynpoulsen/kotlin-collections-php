<?php
declare(strict_types=1);

namespace BradynPoulsen\Kotlin\Types\Internal;

use BradynPoulsen\Kotlin\Types\Common\TypeAssuranceTrait;
use BradynPoulsen\Kotlin\Types\Type;

class NothingType extends AbstractType implements Type
{
    use TypeAssuranceTrait;

    public function __construct()
    {
        parent::__construct('nothing', false, false, false, true);
    }

    public function containsValue($value): bool
    {
        return is_null($value);
    }

    public function containsType(Type $type): bool
    {
        return $type instanceof NothingType;
    }
}
