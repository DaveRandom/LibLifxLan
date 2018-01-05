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

    private static function compareValues($v1, $v2, bool $loose): bool
    {
        return $v1 === $v2 || ($loose && $v1 == $v2);
    }

    private static function searchArrayCaseInsensitive(array $array, string $string): ?string
    {
        foreach ($array as $key => $value) {
            if (\strcasecmp($string, $key) === 0) {
                return $key;
            }
        }

        return null;
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
            if (self::compareValues($memberValue, $searchValue, $looseComparison)) {
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

        if ($caseInsensitive && null !== $key = self::searchArrayCaseInsensitive($constants, $searchName)) {
            return $constants[$key];
        }

        throw new \InvalidArgumentException('Unknown enumeration member: ' . $searchName);
    }

    final public static function toArray(): array
    {
        return self::getClassConstants(static::class);
    }

    final protected function __construct() { }
}
