<?php

namespace Icecave\Parity;

use Icecave\Parity\Comparator\Comparator;
use Icecave\Parity\Comparator\DeepComparator;
use Icecave\Parity\Comparator\ParityComparator;
use Icecave\Parity\Comparator\StrictPhpComparator;

abstract class Parity
{
    /**
     * Compare two values, yeilding a result according to the following table:
     *
     * +--------------------+---------------+
     * | Condition          | Result        |
     * +--------------------+---------------+
     * | $this == $value    | $result === 0 |
     * | $this < $value     | $result < 0   |
     * | $this > $value     | $result > 0   |
     * +--------------------+---------------+
     *
     * @param mixed $lhs The first value to compare.
     * @param mixed $rhs The second value to compare.
     *
     * @return int The result of the comparison.
     */
    public static function compare($lhs, $rhs): int
    {
        return self::comparator()->compare($lhs, $rhs);
    }

    /**
     * @param mixed $lhs The first value to compare.
     * @param mixed $rhs The second value to compare.
     *
     * @return bool True if $lhs == $rhs.
     */
    public static function isEqualTo($lhs, $rhs): bool
    {
        return static::compare($lhs, $rhs) === 0;
    }

    /**
     * @param mixed $lhs The first value to compare.
     * @param mixed $rhs The second value to compare.
     *
     * @return bool True if $lhs != $rhs.
     */
    public static function isNotEqualTo($lhs, $rhs): bool
    {
        return static::compare($lhs, $rhs) !== 0;
    }

    /**
     * @param mixed $lhs The first value to compare.
     * @param mixed $rhs The second value to compare.
     *
     * @return bool True if $lhs < $rhs.
     */
    public static function isLessThan($lhs, $rhs): bool
    {
        return static::compare($lhs, $rhs) < 0;
    }

    /**
     * @param mixed $lhs The first value to compare.
     * @param mixed $rhs The second value to compare.
     *
     * @return bool True if $lhs > $rhs.
     */
    public static function isGreaterThan($lhs, $rhs): bool
    {
        return static::compare($lhs, $rhs) > 0;
    }

    /**
     * @param mixed $lhs The first value to compare.
     * @param mixed $rhs The second value to compare.
     *
     * @return bool True if $lhs <= $rhs.
     */
    public static function isLessThanOrEqualTo($lhs, $rhs): bool
    {
        return static::compare($lhs, $rhs) <= 0;
    }

    /**
     * @param mixed $lhs The first value to compare.
     * @param mixed $rhs The second value to compare.
     *
     * @return bool True if $lhs >= $rhs.
     */
    public static function isGreaterThanOrEqualTo($lhs, $rhs): bool
    {
        return static::compare($lhs, $rhs) >= 0;
    }

    /**
     * @param mixed $lhs     The first value to compare.
     * @param mixed $rhs,... The second (and more) value(s) to compare.
     *
     * @return mixed
     */
    public static function min($lhs, $rhs)
    {
        return self::minSequence(func_get_args());
    }

    /**
     * @param mixed $lhs     The first value to compare.
     * @param mixed $rhs,... The second (and more) value(s) to compare.
     *
     * @return mixed
     */
    public static function max($lhs, $rhs)
    {
        return self::maxSequence(func_get_args());
    }

    /**
     * @param iterable $sequence The sequence to find the minimum value in.
     * @param mixed    $default  The default miniumum value.
     *
     * @return mixed The minimum value in the sequence.
     */
    public static function minSequence(iterable $sequence, $default = null)
    {
        $minAssigned = false;
        $min = null;

        foreach ($sequence as $value) {
            if (!$minAssigned) {
                $minAssigned = true;
                $min = $value;
            } elseif (static::isLessThan($value, $min)) {
                $min = $value;
            }
        }

        if (!$minAssigned) {
            return $default;
        }

        return $min;
    }

    /**
     * @param iterable $sequence The sequence to find the maximum value in.
     * @param mixed    $default  The default maxiumum value.
     *
     * @return mixed The maximum value in the sequence.
     */
    public static function maxSequence(iterable $sequence, $default = null)
    {
        $maxAssigned = false;
        $max = null;

        foreach ($sequence as $value) {
            if (!$maxAssigned) {
                $maxAssigned = true;
                $max = $value;
            } elseif (static::isGreaterThan($value, $max)) {
                $max = $value;
            }
        }

        if (!$maxAssigned) {
            return $default;
        }

        return $max;
    }

    /**
     * Get the internal Parity comparator.
     *
     * The comparator returned by this method in such as way as to enforce the
     * documented rules of Parity's comparison engine.
     *
     * @return Comparator
     */
    public static function comparator(): Comparator
    {
        if (null === self::$comparator) {
            self::$comparator = new ParityComparator(
                new DeepComparator(
                    new StrictPhpComparator()
                )
            );
        }

        return self::$comparator;
    }

    private static $comparator;
}
