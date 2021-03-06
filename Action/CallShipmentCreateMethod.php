<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\ShipXSDK\Client\Client;
use MB\ShipXSDK\Method\Shipment\Create as ShipmentCreateMethod;
use MB\ShipXSDK\Response\Response;

class CallShipmentCreateMethod
{
    private ProvideShipmentPayloadInterface $provideShipmentPayload;

    private ShipmentCreateMethod $shipmentCreateMethod;

    private ProvideOrganizationId $provideOrganizationId;

    public function __construct(
        ProvideShipmentPayloadInterface $provideShipmentPayload,
        ShipmentCreateMethod $shipmentCreateMethod,
        ProvideOrganizationId $provideOrganizationId
    )
    {
        $this->provideShipmentPayload = $provideShipmentPayload;
        $this->shipmentCreateMethod = $shipmentCreateMethod;
        $this->provideOrganizationId = $provideOrganizationId;
    }

    public function execute(Client $client, DataObject $request): Response
    {
        return $client->callMethod(
            $this->shipmentCreateMethod,
            ['organization_id' => $this->provideOrganizationId->execute($request)],
            [],
            $this->provideShipmentPayload->execute($request)
        );
    }
}
