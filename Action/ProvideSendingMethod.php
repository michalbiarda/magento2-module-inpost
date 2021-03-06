<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;

class ProvideSendingMethod implements ProvideSendingMethodInterface
{
    private GetConfigForShippingRequest $getConfigForShippingRequest;

    /**
     * @var string[]
     */
    private array $configPaths;

    public function __construct(GetConfigForShippingRequest $getConfigForShippingRequest, array $configPaths = [])
    {
        $this->getConfigForShippingRequest = $getConfigForShippingRequest;
        $this->configPaths = $configPaths;
    }

    public function execute(DataObject $request): string
    {
        return $this->getConfigForShippingRequest->execute(
            $request,
            $this->configPaths,
            'Sending method config path not defined for shipping method %s',
            'Sending method was not configured.'
        );
    }
}
