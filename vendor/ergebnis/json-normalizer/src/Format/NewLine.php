<?php

declare(strict_types=1);

/**
 * Copyright (c) 2018-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/json-normalizer
 */

namespace Ergebnis\Json\Normalizer\Format;

use Ergebnis\Json\Json;
use Ergebnis\Json\Normalizer\Exception;

/**
 * @psalm-immutable
 */
final class NewLine
{
    private function __construct(private readonly string $value)
    {
    }

    /**
     * @throws Exception\InvalidNewLineString
     */
    public static function fromString(string $value): self
    {
        if (1 !== \preg_match('/^(?>\r\n|\n|\r)$/', $value)) {
            throw Exception\InvalidNewLineString::fromString($value);
        }

        return new self($value);
    }

    public static function fromJson(Json $json): self
    {
        if (1 === \preg_match('/(?P<newLine>\r\n|\n|\r)/', $json->encoded(), $match)) {
            return self::fromString($match['newLine']);
        }

        return self::fromString(\PHP_EOL);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
