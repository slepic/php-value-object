<?php declare(strict_types=1);

namespace Slepic\ValueObject;

interface InvalidValueExceptionInterface extends \Throwable
{
    /**
     * @return mixed
     */
    public function getValue();
}
