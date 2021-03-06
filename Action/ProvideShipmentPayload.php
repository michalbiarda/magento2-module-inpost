<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\Inpost\Exception\ShipmentRequestException;
use MB\ShipXSDK\Model\ShipmentForm;

class ProvideShipmentPayload implements ProvideShipmentPayloadInterface
{
    private ProvideShipmentReceiverInterface $provideShipmentReceiver;

    private ProvideShipmentParcelsInterface $provideShipmentParcels;

    private ProvideShipmentCustomAttributesInterface $provideShipmentCustomAttributes;

    private ProvideShipmentServiceInterface $provideShipmentService;

    private ProvideShipmentAdditionalServicesInterface $provideShipmentAdditionalServices;

    private ProvideShipmentReferenceInterface $provideShipmentReference;

    public function __construct(
        ProvideShipmentReceiverInterface $provideShipmentReceiver,
        ProvideShipmentParcelsInterface $provideShipmentParcels,
        ProvideShipmentCustomAttributesInterface $provideShipmentCustomAttributes,
        ProvideShipmentServiceInterface $provideShipmentService,
        ProvideShipmentAdditionalServicesInterface $provideShipmentAdditionalServices,
        ProvideShipmentReferenceInterface $provideShipmentReference
    )
    {
        $this->provideShipmentReceiver = $provideShipmentReceiver;
        $this->provideShipmentParcels = $provideShipmentParcels;
        $this->provideShipmentCustomAttributes = $provideShipmentCustomAttributes;
        $this->provideShipmentService = $provideShipmentService;
        $this->provideShipmentAdditionalServices = $provideShipmentAdditionalServices;
        $this->provideShipmentReference = $provideShipmentReference;
    }

    /**
     * @todo Cache some data for multipackage requests
     */
    public function execute(DataObject $request): ShipmentForm
    {
        $shipmentForm = new ShipmentForm();
        $shipmentForm->receiver = $this->provideShipmentReceiver->execute($request);
        $shipmentForm->parcels = $this->provideShipmentParcels->execute($request);
        $shipmentForm->custom_attributes = $this->provideShipmentCustomAttributes->execute($request);
        $shipmentForm->service = $this->provideShipmentService->execute($request);
        $shipmentForm->additional_services = $this->provideShipmentAdditionalServices->execute($request);
        $shipmentForm->reference = $this->provideShipmentReference->execute($request);
        return $shipmentForm;
    }
}
