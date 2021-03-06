<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use InvalidArgumentException;
use Magento\Framework\DataObject;
use Magento\Sales\Model\Order\Shipment;

class ProvideTargetPoint implements ProvideShipmentCustomAttributeInterface
{
    public function execute(DataObject $request): ?string
    {
        /** @var Shipment $shipment */
        $shipment = $request->getData('order_shipment');
        if (!is_object($shipment) || !$shipment instanceof Shipment) {
            throw new InvalidArgumentException('Missing order shipment in request.');
        }
        return $shipment->getOrder()->getExtensionAttributes()->getMbInpostLockerName();
    }
}
