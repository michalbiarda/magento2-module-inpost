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
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_TYPE = 'carriers/mbinpost/locker_standard_map_type';
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_SEARCH_TYPE = 'carriers/mbinpost/locker_standard_search_type';
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_GOOGLE_KEY = 'carriers/mbinpost/locker_standard_map_google_key';
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_USE_GEOLOCATION = 'carriers/mbinpost/locker_standard_map_use_geolocation';
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_INITIAL_ZOOM = 'carriers/mbinpost/locker_standard_map_initial_zoom';
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_DETAILS_MIN_ZOOM = 'carriers/mbinpost/locker_standard_map_details_min_zoom';
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_AUTOCOMPLETE_ZOOM = 'carriers/mbinpost/locker_standard_map_autocomplete_zoom';
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_VISIBLE_POINTS_MIN_ZOOM = 'carriers/mbinpost/locker_standard_map_visible_points_min_zoom';
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_DEFAULT_LOCATION_LAT = 'carriers/mbinpost/locker_standard_map_default_location_lat';
    private const XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_DEFAULT_LOCATION_LONG = 'carriers/mbinpost/locker_standard_map_default_location_long';

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

    public function getLockerStandardMapType($storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_TYPE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLockerStandardSearchType($storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_SEARCH_TYPE,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLockerStandardMapGoogleKey($storeId = null): string
    {
        return (string) $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_GOOGLE_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLockerStandardMapUseGeolocation($storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_USE_GEOLOCATION,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLockerStandardMapInitialZoom($storeId = null): int
    {
        return (int) $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_INITIAL_ZOOM,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLockerStandardMapDetailsMinZoom($storeId = null): int
    {
        return (int) $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_DETAILS_MIN_ZOOM,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLockerStandardMapAutocompleteZoom($storeId = null): int
    {
        return (int) $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_AUTOCOMPLETE_ZOOM,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLockerStandardMapVisiblePointsMinZoom($storeId = null): int
    {
        return (int) $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_VISIBLE_POINTS_MIN_ZOOM,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLockerStandardMapDefaultLocationLat($storeId = null): float
    {
        return (float) $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_DEFAULT_LOCATION_LAT,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getLockerStandardMapDefaultLocationLong($storeId = null): float
    {
        return (float) $this->scopeConfig->getValue(
            static::XML_PATH_CARRIERS_MBINPOST_LOCKER_STANDARD_MAP_DEFAULT_LOCATION_LONG,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
