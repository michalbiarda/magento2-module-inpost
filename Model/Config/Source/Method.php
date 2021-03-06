<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Method implements OptionSourceInterface
{
    public function toOptionArray(): array
    {
        return [
            ['value' => 'locker_standard', 'label' => __('Locker Standard')],
            ['value' => 'courier_c2c', 'label' => __('Courier C2C')],
            ['value' => 'courier_standard', 'label' => __('Courier Standard')]
        ];
    }
}
