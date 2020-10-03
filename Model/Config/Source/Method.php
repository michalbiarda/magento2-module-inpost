<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Model\Config\Source;

class Method
{
    /**
     * @return array[]
     */
    public function toOptionArray(): array
    {
        return [
            ['value' => 'locker_standard', 'label' => __('Locker Standard')],
            ['value' => 'courier_standard', 'label' => __('Courier Standard')]
        ];
    }
}
