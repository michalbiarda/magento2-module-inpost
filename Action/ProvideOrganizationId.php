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

class ProvideOrganizationId
{
    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function execute(DataObject $request): int
    {
        /** @var Shipment $shipment */
        $shipment = $request->getData('order_shipment');
        if (!is_object($shipment) || !$shipment instanceof Shipment) {
            throw new InvalidArgumentException('Missing order shipment in request.');
        }
        switch ($this->config->getApiMode($shipment->getStoreId())) {
            case Config\Source\ApiMode::SANDBOX:
                return $this->config->getSandboxOrganizationId();
            case Config\Source\ApiMode::PRODUCTION:
                return $this->config->getProductionOrganizationId();
        }
        return 0;
    }
}
