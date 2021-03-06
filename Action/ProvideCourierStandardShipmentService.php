<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;

class ProvideCourierStandardShipmentService implements ProvideShipmentServiceInterface
{
    public function execute(DataObject $request): string
    {
        return 'inpost_courier_standard';
    }
}