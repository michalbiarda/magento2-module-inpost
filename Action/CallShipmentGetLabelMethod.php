<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use MB\ShipXSDK\Client\Client;
use MB\ShipXSDK\Method\Shipment\GetLabel as ShipmentGetLabelMethod;
use MB\ShipXSDK\Response\Response;

class CallShipmentGetLabelMethod
{
    private ShipmentGetLabelMethod $shipmentGetLabelMethod;

    public function __construct(ShipmentGetLabelMethod $shipmentGetLabelMethod)
    {
        $this->shipmentGetLabelMethod = $shipmentGetLabelMethod;
    }

    public function execute(Client $client, int $shipmentId): Response
    {
        return $client->callMethod($this->shipmentGetLabelMethod, ['shipment_id' => $shipmentId]);
    }
}
