<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\ShipXSDK\Model\TransactionPartyForm;

interface ProvideShipmentReceiverInterface
{
    public function execute(DataObject $request): TransactionPartyForm;
}
