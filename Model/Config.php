<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const XML_PATH_CARRIERS_MBINPOST_API_MODE = 'carriers/mbinpost/api_mode';
    private const XML_PATH_CARRIERS_MBINPOST_SANDBOX_BASE_URI = 'carriers/mbinpost/sandbox_base_uri';
    private const XML_PATH_CARRIERS_MBINPOST_SANDBOX_ORGANIZATION_ID = 'carriers/mbinpost/sandbox_organization_id';
    private const XML_PATH_CARRIERS_MBINPOST_SANDBOX_AUTH_TOKEN = 'carriers/mbinpost/sandbox_auth_token';
    private const XML_PATH_CARRIERS_MBINPOST_PRODUCTION_BASE_URI = 'carriers/mbinpost/production_base_uri';
    private const XML_PATH_CARRIERS_MBINPOST_PRODUCTION_ORGANIZATION_ID = 'carriers/mbinpost/production_organization_id';
    private const XML_PATH_CARRIERS_MBINPOST_PRODUCTION_AUTH_TOKEN = 'carriers/mbinpost/production_auth_token';

    private ScopeConfigInterface $scopeConfig;

    private EncryptorInterface $encryptor;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        EncryptorInterface $encryptor
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->encryptor = $encryptor;
    }

    public function getApiMode($storeId = null): string
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_API_MODE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getSandboxBaseUri(): string
    {
        return $this->scopeConfig->getValue(static::XML_PATH_CARRIERS_MBINPOST_SANDBOX_BASE_URI);
    }

    public function getSandboxOrganizationId(): int
    {
        return (int) $this->scopeConfig->getValue(static::XML_PATH_CARRIERS_MBINPOST_SANDBOX_ORGANIZATION_ID);
    }

    public function getSandboxAuthToken(): string
    {
        $encryptedValue = $this->scopeConfig->getValue(static::XML_PATH_CARRIERS_MBINPOST_SANDBOX_AUTH_TOKEN);
        if (!$encryptedValue) {
            return '';
        }
        return $this->encryptor->decrypt($encryptedValue);
    }

    public function getProductionBaseUri(): string
    {
        return $this->scopeConfig->getValue(static::XML_PATH_CARRIERS_MBINPOST_PRODUCTION_BASE_URI);
    }

    public function getProductionOrganizationId(): int
    {
        return (int) $this->scopeConfig->getValue(static::XML_PATH_CARRIERS_MBINPOST_PRODUCTION_ORGANIZATION_ID);
    }

    public function getProductionAuthToken(): string
    {
        $encryptedValue = $this->scopeConfig->getValue(static::XML_PATH_CARRIERS_MBINPOST_PRODUCTION_AUTH_TOKEN);
        if (!$encryptedValue) {
            return '';
        }
        return $this->encryptor->decrypt($encryptedValue);
    }
}
