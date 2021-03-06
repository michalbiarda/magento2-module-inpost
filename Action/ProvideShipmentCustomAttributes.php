<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\ShipXSDK\Model\ShipmentCustomAttributes;

class ProvideShipmentCustomAttributes implements ProvideShipmentCustomAttributesInterface
{
    /**
     * @var ProvideShipmentCustomAttributeInterface[]
     */
    private array $customAttributeProviders;

    public function __construct(array $customAttributeProviders = [])
    {
        $this->customAttributeProviders = $customAttributeProviders;
    }

    public function execute(DataObject $request): ShipmentCustomAttributes
    {
        $customAttributes = [];
        foreach ($this->customAttributeProviders as $key => $provider) {
            $customAttributes[$key] = $provider->execute($request);
        }
        return new ShipmentCustomAttributes($customAttributes);
    }
}
