<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\Exception\InvalidArgumentException;

use function preg_replace;

class FilterPhoneNumber
{
    public function execute(string $phone): string
    {
        $result = preg_replace('/^(0048|\+48)/', '', $phone);
        $result = preg_replace('/[^0-9]/', '', $result);
        if (strlen($result) !== 9) {
            throw new InvalidArgumentException(__(
                'Recipient\'s phone number is not a valid Polish phone number: %1.',
                $phone)
            );
        }
        return $result;
    }
}
