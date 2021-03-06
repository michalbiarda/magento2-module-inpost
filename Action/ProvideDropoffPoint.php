<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\Inpost\Model\Config\Source\SendingMethod;

class ProvideDropoffPoint implements ProvideDropoffPointInterface
{
    private ProvideSendingMethodInterface $provideSendingMethod;

    private GetConfigForShippingRequest $getConfigForShippingRequest;

    /**
     * @var string[]
     */
    private array $configPaths;

    public function __construct(
        ProvideSendingMethodInterface $provideSendingMethod,
        GetConfigForShippingRequest $getConfigForShippingRequest,
        array $configPaths = []
    ) {
        $this->provideSendingMethod = $provideSendingMethod;
        $this->getConfigForShippingRequest = $getConfigForShippingRequest;
        $this->configPaths = $configPaths;
    }

    public function execute(DataObject $request): ?string
    {
        $sendingMethod = $this->provideSendingMethod->execute($request);
        if ($sendingMethod !== SendingMethod::PARCEL_LOCKER) {
            return null;
        }
        return $this->getConfigForShippingRequest->execute(
            $request,
            $this->configPaths,
            'Drop-off point config path not defined for shipping method %s',
            'Drop-off point was not configured.'
        );
    }
}
