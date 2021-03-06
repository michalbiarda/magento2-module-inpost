<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use MB\ShipXSDK\Client\Client;
use MB\ShipXSDK\Method\Shipment\Read as ShipmentReadMethod;
use MB\ShipXSDK\Response\Response;

class CallShipmentReadMethod
{
    private ShipmentReadMethod $shipmentReadMethod;

    public function __construct(ShipmentReadMethod $shipmentReadMethod)
    {
        $this->shipmentReadMethod = $shipmentReadMethod;
    }

    public function execute(Client $client, int $shipmentId): Response
    {
        return $client->callMethod($this->shipmentReadMethod, ['id' => $shipmentId]);
    }
}
