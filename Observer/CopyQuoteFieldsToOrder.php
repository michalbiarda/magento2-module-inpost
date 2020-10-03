<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Observer;

use Magento\Framework\DataObject\Copy;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote;
use Magento\Sales\Model\Order;

class CopyQuoteFieldsToOrder implements ObserverInterface
{
    /**
     * @var Copy
     */
    private $objectCopyService;

    /**
     * @param Copy $objectCopyService
     */
    public function __construct(Copy $objectCopyService)
    {
        $this->objectCopyService = $objectCopyService;
    }

    public function execute(Observer $observer): void
    {
        /* @var Order $order */
        $order = $observer->getEvent()->getData('order');
        /* @var Quote $quote */
        $quote = $observer->getEvent()->getData('quote');
        $this->objectCopyService
            ->copyFieldsetToTarget('mb_inpost_sales_convert_quote', 'to_order', $quote, $order);
    }
}
