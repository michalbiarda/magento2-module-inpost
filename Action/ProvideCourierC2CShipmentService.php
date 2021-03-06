<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;

class ProvideCourierC2CShipmentService implements ProvideShipmentServiceInterface
{
    public function execute(DataObject $request): string
    {
        return 'inpost_courier_c2c';
    }
}