<?php declare(strict_types=1);

namespace DaveRandom\LibLifxLan;

abstract class Enum
{
    private static $constantCache = [];

    /**
     * Get a map of member names to values for the specified class name
     *
     * @param string $className
     * @return array
     */
    private static function getClassConstants(string $className): array
    {
        return self::$constantCache[$className]
            ?? self::$constantCache[$className] = (new \ReflectionClass($className))->getConstants();
    }

    /**
     * Get the name of the first member with the specified value
     *
     * @param mixed $searchValue
     * @param bool $looseComparison
     * @return string
     */
    final public static function parseValue($searchValue, bool $looseComparison = false): string
    {
        foreach (self::getClassConstants(static::class) as $name => $memberValue) {
            if ($searchValue === $memberValue || ($looseComparison && $searchValue == $memberValue)) {
                return $name;
            }
        }

        throw new \InvalidArgumentException('Unknown enumeration value: ' . $searchValue);
    }

    /**
     * Get the value of the member with the specified name
     *
     * @param string $searchName
     * @param bool $caseInsensitive
     * @return mixed
     */
    final public static function parseName(string $searchName, bool $caseInsensitive = false)
    {
        $constants = self::getClassConstants(static::class);

        if (isset($constants[$searchName])) {
            return $constants[$searchName];
        }

        if ($caseInsensitive) {
            foreach ($constants as $memberName => $value) {
                if (\strcasecmp($searchName, $memberName) === 0) {
                    return $value;
                }
            }
        }

        throw new \InvalidArgumentException('Unknown enumeration member: ' . $searchName);
    }

    final protected function __construct() { }
}
