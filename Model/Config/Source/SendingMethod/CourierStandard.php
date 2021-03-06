<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model\Config\Source\SendingMethod;

use Magento\Framework\Data\OptionSourceInterface;
use MB\Inpost\Model\Config\Source\SendingMethod;

use function array_filter;
use function array_merge;
use function in_array;

class CourierStandard implements OptionSourceInterface
{
    private SendingMethod $sendingMethodSource;

    public function __construct(SendingMethod $sendingMethodSource)
    {
        $this->sendingMethodSource = $sendingMethodSource;
    }

    public function toOptionArray(): array
    {
        return array_merge(
            [['value' => '', 'label' => '']],
            array_filter($this->sendingMethodSource->toOptionArray(), function (array $item) {
                return in_array(
                    $item['value'],
                    [
                        SendingMethod::COURIER_POK,
                        SendingMethod::BRANCH,
                        SendingMethod::DISPATCH_ORDER,
                        SendingMethod::POP
                    ]
                );
            })
        );
    }
}
