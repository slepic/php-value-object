<?php

namespace Slepic\ValueObject;

interface InvalidValueExceptionInterface extends \Throwable
{
    /**
     * @return mixed
     */
    public function getValue();
    public function getExpectation(): ?string;
}
