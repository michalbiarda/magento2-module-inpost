<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\InvalidArgumentException;
use MB\ShipXSDK\Model\DimensionsSimple;
use MB\ShipXSDK\Model\ParcelsSimple;
use MB\ShipXSDK\Model\WeightSimple;

use function preg_replace;

/**
 * @todo Use Zend Measure to convert units.
 */
class ProvideShipmentParcels implements ProvideShipmentParcelsInterface
{
    public function execute(DataObject $request): array
    {
        $parcel = new ParcelsSimple();
        $parcel->id = (int) $request->getData('package_id');
        $this->setTemplate($parcel, $request);
        if (!$parcel->template) {
            $this->setWeight($request, $parcel);
            $this->setDimensions($request, $parcel);
        }
        return [$parcel];
    }

    private function setTemplate(ParcelsSimple $parcel, DataObject $request): void
    {
        $parcel->is_non_standard = false;
        $packagingType = $request->getData('packaging_type') ?? null;
        if ($packagingType) {
            $parcel->template = preg_replace(
                '/^' . $request->getData('shipping_method') . '_/',
                '',
                $packagingType
            );
        }
    }

    private function setWeight(DataObject $request, ParcelsSimple $parcel): void
    {
        $weight = new WeightSimple();
        $weight->amount = (float)$request->getData('package_weight') ?? 0;
        if (!$weight->amount) {
            throw new InvalidArgumentException(__('Parcel weight is required.'));
        }
        $weightUnits = $request->getData('package_params')->getData('weight_units') ?? null;
        if ($weightUnits !== \Zend_Measure_Weight::KILOGRAM) {
            throw new InvalidArgumentException(__('The only allowed weight unit is kilogram.'));
        }
        $weight->unit = 'kg';
        $parcel->weight = $weight;
    }

    private function setDimensions(DataObject $request, ParcelsSimple $parcel): void
    {
        $dimensions = new DimensionsSimple();
        $dimensions->length = (float)$request->getData('package_params')->getData('length') * 10 ?? 0;
        if (!$dimensions->length) {
            throw new InvalidArgumentException(__('Parcel length is required.'));
        }
        $dimensions->width = (float)$request->getData('package_params')->getData('width') * 10 ?? 0;
        if (!$dimensions->width) {
            throw new InvalidArgumentException(__('Parcel width is required.'));
        }
        $dimensions->height = (float)$request->getData('package_params')->getData('height') * 10 ?? 0;
        if (!$dimensions->height) {
            throw new InvalidArgumentException(__('Parcel height is required.'));
        }
        $dimensionUnits = $request->getData('package_params')->getData('dimension_units') ?? null;
        if ($dimensionUnits !== \Zend_Measure_Length::CENTIMETER) {
            throw new InvalidArgumentException(__('The only allowed dimensions unit is centimeter.'));
        }
        $dimensions->unit = 'mm';
        $parcel->dimensions = $dimensions;
    }
}
