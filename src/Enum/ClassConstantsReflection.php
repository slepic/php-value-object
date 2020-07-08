<?php declare(strict_types=1);

namespace Slepic\ValueObject\Enum;

class ClassConstantsReflection
{
    /**
     * @param \ReflectionClass $reflection
     * @return array<string, string>
     */
    public static function getUniqueStringConstantValues(\ReflectionClass $reflection): array
    {
        $all = [];
        $constants = $reflection->getReflectionConstants();
        foreach ($constants as $constant) {
            if ($constant->isPublic()) {
                $name = $constant->getName();
                $value = $constant->getValue();
                if (!\is_string($value)) {
                    throw new \UnexpectedValueException("Constant \"$name\" does not have a string value.");
                }
                if (isset($all[$value])) {
                    throw new \UnexpectedValueException("Constant \"$name\" has duplicate value.");
                }
                $all[$value] = $value;
            }
        }
        return $all;
    }
}
