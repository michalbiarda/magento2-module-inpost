<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;

use MB\Inpost\Model\ContainerType;
use MB\Inpost\Model\ContainerTypeCollection;
use function is_null;
use const ARRAY_FILTER_USE_BOTH;

class GetContainerTypesOptions
{
    private ContainerTypeCollection $containerTypeCollection;

    public function __construct(ContainerTypeCollection $containerTypeCollection)
    {
        $this->containerTypeCollection = $containerTypeCollection;
    }

    public function execute(?DataObject $params = null): array
    {
        $containerTypes = $this->convertToFlatArray($this->containerTypeCollection->getItems());
        if (is_null($params)) {
            return $containerTypes;
        }
        $method = $params->getData('method') ?? null;
        if (!$method) {
            return [];
        }
        return array_merge(
            ['' => ''],
            array_filter($containerTypes, function ($value, $key) use ($method) {
                return strpos($key, $method) === 0;
            }, ARRAY_FILTER_USE_BOTH)
        );
    }

    /**
     * @param ContainerType[] $containerTypes
     * @return string[]
     */
    private function convertToFlatArray(array $containerTypes): array
    {
        $result = [];
        foreach ($containerTypes as $key => $containerType) {
            $result[$key] = $containerType->getLabel();
        }
        return $result;
    }
}
