<?php declare(strict_types=1);

namespace Slepic\Tests\ValueObject\Collections;

use Slepic\ValueObject\Collections\DataStructure;

final class DataStructureFixture extends DataStructure
{
    public int $x;
    protected float $y;
    private string $z;

    public function __construct(int $x, float $y, string $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getZ(): string
    {
        return $this->z;
    }
}
