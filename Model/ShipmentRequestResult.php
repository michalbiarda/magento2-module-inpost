<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model;

use Magento\Framework\DataObject;

class ShipmentRequestResult extends DataObject
{
    public function __construct(string $trackingNumber, string $shippingLabelContent, array $data = [])
    {
        parent::__construct($data);
        $this->setData('tracking_number', $trackingNumber);
        $this->setData('shipping_label_content', $shippingLabelContent);
    }

    public function getTrackingNumber(): string
    {
        return $this->getData('tracking_number');
    }

    public function getShippingLabelContent(): string
    {
        return $this->getData('shipping_label_content');
    }
}
