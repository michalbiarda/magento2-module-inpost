<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\ViewModel;

use Magento\Framework\Exception\InvalidArgumentException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use MB\Inpost\Model\Config;

class Map implements ArgumentInterface
{
    private SerializerInterface $serializer;

    private Config $config;

    public function __construct(SerializerInterface $serializer, Config $config)
    {
        $this->serializer = $serializer;
        $this->config = $config;
    }

    public function getJsConfig(): string
    {
        $mapType = $this->config->getLockerStandardMapType();
        $searchType = $this->config->getLockerStandardSearchType();
        $config = [
            'mapType' => $mapType,
            'searchType' => $searchType,
            'defaultLocale' => 'pl',
            'apiEndpoint' => 'https://api-pl-points.easypack24.net/v1',
            'map' => [
                'useGeolocation' => $this->config->getLockerStandardMapUseGeolocation(),
                'initialZoom' => $this->config->getLockerStandardMapInitialZoom(),
                'detailsMinZoom' => $this->config->getLockerStandardMapDetailsMinZoom(),
                'autocompleteZoom' => $this->config->getLockerStandardMapAutocompleteZoom(),
                'visiblePointsMinZoom' => $this->config->getLockerStandardMapVisiblePointsMinZoom(),
                'defaultLocation' => [
                    $this->config->getLockerStandardMapDefaultLocationLat(),
                    $this->config->getLockerStandardMapDefaultLocationLong()
                ],
                'initialTypes' => ['pop', 'parcel_locker']
            ]
        ];
        if ($mapType === Config\Source\MapType::GOOGLE || $searchType === Config\Source\MapType::GOOGLE) {
            $googleKey = $this->config->getLockerStandardMapGoogleKey();
            if (!$googleKey) {
                throw new InvalidArgumentException(__('Google key must not be empty'));
            }
            $config['map']['googleKey'] = $googleKey;
        }
        return $this->serializer->serialize($config);
    }
}
