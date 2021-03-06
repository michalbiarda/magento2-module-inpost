<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\Inpost\Exception\ShipmentRequestException;
use MB\Inpost\Model\ShipmentRequestResult;
use MB\ShipXSDK\Model\BinaryContent;
use MB\ShipXSDK\Model\Error;
use MB\ShipXSDK\Model\Shipment;
use Psr\Log\LoggerInterface;

class ProcessShipmentRequest implements ProcessShipmentRequestInterface
{
    private const READ_CALL_INTERVAL = 2;
    private const READ_CALL_MAX_TRIES = 10;

    private ProvideApiClient $clientProvider;

    private CallShipmentCreateMethod $callShipmentCreateMethod;

    private CallShipmentReadMethod $callShipmentReadMethod;

    private CallShipmentGetLabelMethod $callShipmentGetLabelMethod;

    private LoggerInterface $logger;

    public function __construct(
        ProvideApiClient $clientProvider,
        CallShipmentCreateMethod $callShipmentCreateMethod,
        CallShipmentReadMethod $callShipmentReadMethod,
        CallShipmentGetLabelMethod $callShipmentGetLabelMethod,
        LoggerInterface $logger
    ) {
        $this->clientProvider = $clientProvider;
        $this->callShipmentCreateMethod = $callShipmentCreateMethod;
        $this->callShipmentReadMethod = $callShipmentReadMethod;
        $this->callShipmentGetLabelMethod = $callShipmentGetLabelMethod;
        $this->logger = $logger;
    }

    public function execute(DataObject $request): ShipmentRequestResult
    {
        $client = $this->clientProvider->execute($request);
        $response = $this->callShipmentCreateMethod->execute($client, $request);
        $responsePayload = $response->getPayload();
        if (!$response->getSuccess()) {
            /** @var Error $responsePayload */
            $this->logger->error($responsePayload->message, $responsePayload->toArray());
            if ($responsePayload->error === 'validation_failed') {
                throw new ShipmentRequestException(
                    __('Validation error occurred while creating shipment. Check logs for details.')
                );
            }
            throw new ShipmentRequestException(
                __('An unexpected error occurred while creating shipment. Check logs for details.')
            );
        }
        $shipmentCreated = false;
        /** @var Shipment $responsePayload */
        for ($i = 0; $i < self::READ_CALL_MAX_TRIES; $i++) {
            \sleep(self::READ_CALL_INTERVAL);
            $response = $this->callShipmentReadMethod->execute($client, $responsePayload->id);
            $responsePayload = $response->getPayload();
            if (!$response->getSuccess()) {
                /** @var Error $responsePayload */
                $this->logger->error($responsePayload->message, $responsePayload->toArray());
                continue;
            }
            /** @var Shipment $responsePayload */
            if ($responsePayload->status === 'confirmed') {
                $shipmentCreated = true;
                break;
            }
        }
        if (!$shipmentCreated) {
            if ($response->getSuccess()) {
                $this->logger->error(
                    'Shipment was created, but it did not reach "confirmed" status.',
                    $responsePayload->toArray()
                );
            }
            throw new ShipmentRequestException(
                __('Shipment was created, but it did not reach "confirmed" status. Please try again.')
            );
        }
        $trackingNumber = $responsePayload->tracking_number;
        $response = $this->callShipmentGetLabelMethod->execute($client, $responsePayload->id);
        $responsePayload = $response->getPayload();
        if (!$response->getSuccess()) {
            /** @var Error $responsePayload */
            $this->logger->error($responsePayload->message, $responsePayload->toArray());
            throw new ShipmentRequestException(__('Shipment was successfully created but an unexpected error occurred '
                . 'while downloading shipment label. Check logs for details.'));
        }
        /** @var BinaryContent $responsePayload */
        return new ShipmentRequestResult($trackingNumber, $responsePayload->stream->getContents());
    }
}
