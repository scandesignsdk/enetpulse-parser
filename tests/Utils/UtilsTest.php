<?php

namespace SDM\Enetpulse\Tests\Utils;

use PHPUnit\Framework\TestCase;
use SDM\Enetpulse\Utils\Utils;

class UtilsTest extends TestCase
{
    public function dataProvider(): array
    {
        return [
            ['yes', true],
            [1, true],
            ['y', true],
            [0, false],
            ['no', false],
            ['yEs', true],
            ['nO', false],
            [true, true],
            [false, false],
        ];
    }

    /**
     * @dataProvider dataProvider
     *
     * @param mixed $item
     * @param bool  $expected
     */
    public function testCreateBool($item, bool $expected): void
    {
        $this->assertSame($expected, Utils::createBool($item));
    }
}
