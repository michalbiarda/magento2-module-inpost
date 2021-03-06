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
use MB\Inpost\Model\Config;
use MB\ShipXSDK\Client\Client;
use MB\ShipXSDK\Client\ClientFactory;

class ProvideApiClient
{
    private Config $config;

    private ClientFactory $clientFactory;

    public function __construct(
        Config $config,
        ClientFactory $clientFactory
    ) {
        $this->config = $config;
        $this->clientFactory = $clientFactory;
    }

    public function execute(DataObject $request): Client
    {
        /** @var Shipment $shipment */
        $shipment = $request->getData('order_shipment');
        if (!is_object($shipment) || !$shipment instanceof Shipment) {
            throw new InvalidArgumentException('Missing order shipment in request.');
        }
        switch ($this->config->getApiMode($shipment->getStoreId())) {
            case Config\Source\ApiMode::SANDBOX:
                return $this->clientFactory->create([
                    'baseUri' => $this->config->getSandboxBaseUri(),
                    'authToken' => $this->config->getSandboxAuthToken()
                ]);
            case Config\Source\ApiMode::PRODUCTION:
                return $this->clientFactory->create([
                    'baseUri' => $this->config->getProductionBaseUri(),
                    'authToken' => $this->config->getProductionAuthToken()
                ]);
        }
        throw new \RuntimeException('Missing configuration for client creation.');
    }
}
