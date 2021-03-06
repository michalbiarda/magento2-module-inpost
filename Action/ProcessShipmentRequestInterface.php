<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\Inpost\Exception\ShipmentRequestException;
use MB\Inpost\Model\ShipmentRequestResult;

interface ProcessShipmentRequestInterface
{
    /**
     * @param DataObject $request
     * @return ShipmentRequestResult
     * @throws ShipmentRequestException
     */
    public function execute(DataObject $request): ShipmentRequestResult;
}
