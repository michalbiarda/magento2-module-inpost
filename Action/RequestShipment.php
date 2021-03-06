<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\Inpost\Exception\ShipmentRequestException;
use MB\Inpost\Model\ShipmentRequestError;
use Psr\Log\LoggerInterface;

class RequestShipment
{
    private GetShippingMethodFromShipment $getShippingMethodFromShipment;

    private LoggerInterface $logger;

    /**
     * @var ProcessShipmentRequestInterface[]
     */
    private array $shipmentRequestProcessors;

    public function __construct(
        GetShippingMethodFromShipment $getShippingMethodFromShipment,
        LoggerInterface $logger,
        array $shipmentRequestProcessors = []
    ) {
        $this->getShippingMethodFromShipment = $getShippingMethodFromShipment;
        $this->logger = $logger;
        $this->shipmentRequestProcessors = $shipmentRequestProcessors;
    }

    public function execute(DataObject $request): DataObject
    {
        try {
            $methodCode = $this->getShippingMethodFromShipment->execute($request->getData('order_shipment'));
        } catch (\Throwable $throwable) {
            $this->logger->error($throwable->getMessage());
            return new ShipmentRequestError((string) __(
                'An unexpected error occurred while processing this shipment request. Check logs for more details.'
            ));
        }

        foreach ($this->shipmentRequestProcessors as $key => $shipmentRequestProcessor) {
            if ($key === $methodCode) {
                try {
                    return $shipmentRequestProcessor->execute($request);
                } catch (ShipmentRequestException $e) {
                    return new ShipmentRequestError($e->getMessage());
                }
            }
        }

        return new ShipmentRequestError(
            (string) __('This feature is not implemented for shipping method chosen for this order.')
        );
    }
}
