<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Test\Unit\Action;

use InvalidArgumentException;
use MB\Inpost\Action\FilterPhoneNumber;
use PHPUnit\Framework\TestCase;

class FilterPhoneNumberTest extends TestCase
{
    private FilterPhoneNumber $filterPhoneNumber;

    public function setUp(): void
    {
        $this->filterPhoneNumber = new FilterPhoneNumber();
    }

    /**
     * @dataProvider correctPhoneNumbersProvider
     * @param string $input
     * @param string $expectedOutput
     */
    public function testFilterReturnsNineDigitsForCorrectNumber(string $input, string $expectedOutput): void
    {
        $result = $this->filterPhoneNumber->execute($input);
        $this->assertSame($expectedOutput, $result);
    }

    /**
     * @dataProvider incorrectPhoneNumbersProvider
     * @param string $input
     */
    public function testFilterThrowsExceptionForIncorrectNumber(string $input): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->filterPhoneNumber->execute($input);
    }

    public function correctPhoneNumbersProvider(): array
    {
        return [
            ['123456789', '123456789'],
            ['500-123-456', '500123456'],
            ['22 3456789', '223456789'],
            ['(22)8881234', '228881234'],
            ['0048987654321', '987654321'],
            ['+48234567890', '234567890']
        ];
    }

    public function incorrectPhoneNumbersProvider(): array
    {
        return [
            ['0049987654321'],
            ['9876540048321'],
            ['+49234567890'],
            ['12345678'],
            ['48123456789'],
            ['1234567890123'],
            ['letters']
        ];
    }
}
