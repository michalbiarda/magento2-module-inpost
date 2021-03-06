<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;

class GetConfigForShippingRequest
{
    private ScopeConfigInterface $config;

    public function __construct(ScopeConfigInterface $config)
    {
        $this->config = $config;
    }

    public function execute(
        DataObject $request,
        array $configPaths,
        string $notDefinedMessage,
        string $notConfiguredMessage
    ): string {
        if (!isset($configPaths[$request->getData('shipping_method')])) {
            throw new \InvalidArgumentException(sprintf(
                $notDefinedMessage,
                $request->getData('shipping_method')
            ));
        }
        $value = $this->config->getValue($configPaths[$request->getData('shipping_method')]);
        if (empty($value)) {
            throw new LocalizedException(__($notConfiguredMessage));
        }
        return (string) $value;
    }
}
