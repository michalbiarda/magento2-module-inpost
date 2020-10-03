<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Plugin;

use Magento\Quote\Model\Quote;
use Magento\Quote\Model\QuoteRepository\LoadHandler;

class SetLockerDataOnQuoteAfterLoad
{
    /**
     * @param LoadHandler $subject
     * @param Quote $quote
     * @return Quote
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterLoad(LoadHandler $subject, Quote $quote): Quote
    {
        $extensionAttributes = $quote->getExtensionAttributes();
        $extensionAttributes->setMbInpostLockerName($quote->getData('mb_inpost_locker_name'));
        $extensionAttributes->setMbInpostLockerAddressLine1($quote->getData('mb_inpost_locker_address_line_1'));
        $extensionAttributes->setMbInpostLockerAddressLine2($quote->getData('mb_inpost_locker_address_line_2'));
        return $quote;
    }
}
