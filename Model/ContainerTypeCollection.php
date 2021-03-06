<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Model;

class ContainerTypeCollection
{
    /**
     * @var ContainerType[]
     */
    private array $containerTypes;

    public function __construct(array $containerTypes = [])
    {
        foreach ($containerTypes as $containerType) {
            if (!$containerType instanceof ContainerType) {
                throw new \InvalidArgumentException('Array of container type models required.');
            }
        }
        $this->containerTypes = $containerTypes;
    }

    /**
     * @return ContainerType[]
     */
    public function getItems(): array
    {
        return $this->containerTypes;
    }
}