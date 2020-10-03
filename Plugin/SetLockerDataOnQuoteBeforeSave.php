<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Plugin;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository\SaveHandler;

class SetLockerDataOnQuoteBeforeSave
{
    /**
     * @param SaveHandler $subject
     * @param Quote $quote
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(SaveHandler $subject, Quote $quote): void
    {
        $extensionAttributes = $quote->getExtensionAttributes();
        $quote->setData('mb_inpost_locker_name', $extensionAttributes->getMbInpostLockerName());
        $quote->setData('mb_inpost_locker_address_line_1', $extensionAttributes->getMbInpostLockerAddressLine1());
        $quote->setData('mb_inpost_locker_address_line_2', $extensionAttributes->getMbInpostLockerAddressLine2());
    }
}
