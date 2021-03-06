<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Sales\Api\Data\ShipmentInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Order;

class GetShippingMethodFromShipment
{
    private Order $orderResource;

    private OrderFactory $orderFactory;

    public function __construct(
        Order $orderResource,
        OrderFactory $orderFactory
    ) {
        $this->orderResource = $orderResource;
        $this->orderFactory = $orderFactory;
    }

    public function execute(ShipmentInterface $shipment): string
    {
        $order = $this->orderFactory->create();
        $this->orderResource->load($order, $shipment->getOrderId());
        return (string) $order->getShippingMethod();
    }
}
