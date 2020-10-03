<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Model;

use Magento\Checkout\Api\Data\ShippingInformationExtensionInterface;
use Magento\Framework\Exception\LocalizedException;

class LockerValidator
{
    /**
     * @param  ShippingInformationExtensionInterface $extensionAttributes
     * @return bool
     * @throws LocalizedException
     */
    public function validate(ShippingInformationExtensionInterface $extensionAttributes): bool
    {
        if (!$extensionAttributes->getMbInpostLockerName()
            || !$extensionAttributes->getMbInpostLockerAddressLine1()
            || !$extensionAttributes->getMbInpostLockerAddressLine2()
        ) {
            throw new LocalizedException(__('You need to choose a locker.'));
        }
        return true;
    }
}
