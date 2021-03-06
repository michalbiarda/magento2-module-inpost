<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Plugin;

use Magento\Framework\Serialize\SerializerInterface;
use Magento\Sales\Model\Order\Shipment;
use Magento\Shipping\Block\Adminhtml\Order\Packaging;
use MB\Inpost\Action\GetShippingMethodFromShipment;
use MB\Inpost\Model\ContainerTypeCollection;

class CustomizePackagingJsModuleConfig
{
    private SerializerInterface $serializer;

    private GetShippingMethodFromShipment $getShippingMethodFromShipment;

    private ContainerTypeCollection $containerTypeCollection;

    public function __construct(
        SerializerInterface $serializer,
        GetShippingMethodFromShipment $getShippingMethodFromShipment,
        ContainerTypeCollection $containerTypeCollection
    ) {
        $this->serializer = $serializer;
        $this->containerTypeCollection = $containerTypeCollection;
        $this->getShippingMethodFromShipment = $getShippingMethodFromShipment;
    }

    public function afterGetConfigDataJson(Packaging $subject, string $result): string
    {
        $jsConfig = $this->serializer->unserialize($result);
        $jsConfig['mbinpost'] = ['enabled' => $this->isInpostShipment($subject->getShipment())];
        if ($jsConfig['mbinpost']['enabled']) {
            $jsConfig['mbinpost']['containerTypes'] = $this->getContainerTypes();
        }
        return $this->serializer->serialize($jsConfig);
    }

    private function isInpostShipment(Shipment $shipment): bool
    {
        return strpos($this->getShippingMethodFromShipment->execute($shipment), 'mbinpost_') === 0;
    }

    public function getContainerTypes(): array
    {
        $containerTypes = [];
        foreach ($this->containerTypeCollection->getItems() as $key => $containerType) {
            $containerTypes[$key] = [
                'width' => $containerType->getWidth(),
                'height' => $containerType->getHeight(),
                'length' => $containerType->getLength(),
                'weight' => $containerType->getWeight()
            ];
        }
        return $containerTypes;
    }
}
