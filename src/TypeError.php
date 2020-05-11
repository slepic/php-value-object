<?php

namespace Slepic\ValueObject;

final class TypeError
{
    private function __construct()
    {
    }

    public static function getExpectedType(\TypeError $error): ?string
    {
        $expectedType = null;
        $lastFrame = $error->getTrace()[0] ?? null;
        if ($lastFrame !== null) {
            $function = $lastFrame['function'] ?? null;
            $class = $lastFrame['class'] ?? null;
            if ($class !== null && $function !== null) {
                $ref = new \ReflectionMethod($class, $function);
                $param = $ref->getParameters()[0] ?? null;
                if ($param !== null && $param->hasType()) {
                    $type = $param->getType();
                    if ($type instanceof \ReflectionNamedType && $type->isBuiltin()) {
                        $expectedType = $type->getName();
                    }
                }
            }
        }
        return $expectedType;
    }
}
