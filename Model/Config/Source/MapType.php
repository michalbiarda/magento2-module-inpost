<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class MapType implements OptionSourceInterface
{
    public const OSM = 'osm';
    public const GOOGLE = 'google';

    public function toOptionArray(): array
    {
        return [
            ['value' => self::OSM, 'label' => __('Open Street Map')],
            ['value' => self::GOOGLE, 'label' => __('Google Map')]
        ];
    }
}
