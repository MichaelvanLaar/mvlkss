<?php

namespace Icecave\Repr;

use Eloquent\Phony\Phpunit\Phony;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

class GeneratorTest extends TestCase
{
    public function setUp(): void
    {
        $this->_generator = new Generator();
    }

    public function testNull()
    {
        $this->assertSame('null', $this->_generator->generate(null));
    }

    public function testArrayEmpty()
    {
        $this->assertSame('[]', $this->_generator->generate([]));
    }

    public function testArrayVector()
    {
        $this->assertSame(
            '[1, 2, 3]',
            $this->_generator->generate(
                [1, 2, 3]
            )
        );
    }

    public function testArrayAssociative()
    {
        $this->assertSame(
            '["a" => 1, "b" => 2, "c" => 3]',
            $this->_generator->generate(
                ['a' => 1, 'b' => 2, 'c' => 3]
            )
        );
    }

    public function testArrayTrimmedVector()
    {
        $this->assertSame(
            '["foo", "bar", "spam", <+2>]',
            $this->_generator->generate(
                ['foo', 'bar', 'spam', 'doom', 'quux']
            )
        );
    }

    public function testArrayTrimmedAssociative()
    {
        $this->assertSame(
            '["a" => 1, "b" => 2, "c" => 3, <+2>]',
            $this->_generator->generate(
                [
                    'a' => 1,
                    'b' => 2,
                    'c' => 3,
                    'd' => 4,
                    'e' => 5,
                ]
            )
        );
    }

    public function testArrayNestedArraysAtMaximumDepth()
    {
        $input = [
            [
                [1, 2, 3],
                [4, 5, 6],
                [7, 8, 9],
            ],
        ];

        $expected = '[[[1, 2, 3], [4, 5, 6], [7, 8, 9]]]';
        $this->assertSame($expected, $this->_generator->generate($input));
    }

    public function testArrayNestedArraysExceedingMaximumDepth()
    {
        $input = [
            [
                [
                    [1, 2, 3],
                    [4, 5, 6],
                    [7, 8, 9],
                ],
            ],
        ];

        $expected = '[[[[<3>], [<3>], [<3>]]]]';
        $this->assertSame($expected, $this->_generator->generate($input));
    }

    public function testObject()
    {
        $object = new stdClass();
        $this->assertSame('<stdClass @ ' . spl_object_hash($object) . '>', $this->_generator->generate($object));
    }

    public function testObjectWithToString()
    {
        $handle = Phony::mock(ReflectionClass::class);
        $handle->__toString->returns('foo');
        $object = $handle->get();

        $this->assertSame('<' . get_class($object) . ' "foo" @ ' . spl_object_hash($object) . '>', $this->_generator->generate($object));
    }

    public function testRepresentableObject()
    {
        $handle = Phony::mock(RepresentableInterface::class);
        $handle->stringRepresentation->returns('<foo>');
        $object = $handle->get();

        $this->assertSame('<foo>', $this->_generator->generate($object));

        $handle->stringRepresentation->calledWith($this->_generator, 0);
    }

    public function testStream()
    {
        $resource = fopen('php://memory', 'rb');
        $this->assertSame('<resource: stream #' . intval($resource) . ' rb>', $this->_generator->generate($resource));
    }

    public function testResource()
    {
        $resource = stream_context_create();
        $this->assertSame('<resource: stream-context #' . intval($resource) . '>', $this->_generator->generate($resource));
    }

    public function testString()
    {
        $input    = "foo\n\r\t\v\f\\\$\"\x00bar";
        $expected = '"foo\n\r\t\v\f\\\\\$\"\x00bar"';
        $this->assertSame($expected, $this->_generator->generate($input));
    }

    public function testStringEscape()
    {
        if (version_compare(PHP_VERSION, '5.4.0', '<')) {
            $this->markTestSkipped('Requires PHP v5.4');
        }
        $this->assertSame('"\e"', $this->_generator->generate("\e"));
    }

    public function testStringTrimmed()
    {
        $input    = '01234567890123456789012345678901234567890123456789xxx';
        $expected = '"01234567890123456789012345678901234567890123456789...';
        $this->assertSame($expected, $this->_generator->generate($input));
    }

    public function testInteger()
    {
        $this->assertSame('100', $this->_generator->generate(100));
    }

    public function testFloat()
    {
        $this->assertSame('0.0', $this->_generator->generate(0.0));
        $this->assertSame('0.05', $this->_generator->generate(0.05));
        $this->assertSame('100.0', $this->_generator->generate(100.0));
        $this->assertSame('100.25', $this->_generator->generate(100.25));
    }

    public function testBoolean()
    {
        $this->assertSame('true', $this->_generator->generate(true));
        $this->assertSame('false', $this->_generator->generate(false));
    }
}
