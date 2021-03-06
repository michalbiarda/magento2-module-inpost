<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class SendingMethod implements OptionSourceInterface
{
    public const PARCEL_LOCKER = 'parcel_locker';
    public const POK = 'pok';
    public const POP = 'pop';
    public const COURIER_POK = 'courier_pok';
    public const BRANCH = 'branch';
    public const DISPATCH_ORDER = 'dispatch_order';

    public function toOptionArray(): array
    {
        return [
            ['value' => self::PARCEL_LOCKER, 'label' => __('Post at parcel locker')],
            ['value' => self::POK, 'label' => __('Post at customer service office')],
            ['value' => self::POP, 'label' => __('Post at shipping point')],
            ['value' => self::COURIER_POK, 'label' => __('Post at customer service office')],
            ['value' => self::BRANCH, 'label' => __('Post at branch')],
            ['value' => self::DISPATCH_ORDER, 'label' => __('Collect by courier')]
        ];
    }
}
