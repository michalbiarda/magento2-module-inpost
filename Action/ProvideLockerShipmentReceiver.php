<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\ShipXSDK\Model\TransactionPartyForm;

class ProvideLockerShipmentReceiver implements ProvideShipmentReceiverInterface
{
    private FilterPhoneNumber $filterPhoneNumber;

    public function __construct(FilterPhoneNumber $filterPhoneNumber)
    {
        $this->filterPhoneNumber = $filterPhoneNumber;
    }

    public function execute(DataObject $request): TransactionPartyForm
    {
        $receiver = new TransactionPartyForm();
        $receiver->email = $request->getData('recipient_email');
        $receiver->phone = $this->filterPhoneNumber->execute(
            (string) $request->getData('recipient_contact_phone_number')
        );
        return $receiver;
    }
}
