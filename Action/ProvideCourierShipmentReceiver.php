<?php

/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace MB\Inpost\Action;

use Magento\Framework\DataObject;
use MB\ShipXSDK\Model\AddressForm;
use MB\ShipXSDK\Model\TransactionPartyForm;

class ProvideCourierShipmentReceiver implements ProvideShipmentReceiverInterface
{
    private ProvideLockerShipmentReceiver $provideLockerShipmentReceiver;

    public function __construct(ProvideLockerShipmentReceiver $provideLockerShipmentReceiver)
    {
        $this->provideLockerShipmentReceiver = $provideLockerShipmentReceiver;
    }

    public function execute(DataObject $request): TransactionPartyForm
    {
        $receiver = $this->provideLockerShipmentReceiver->execute($request);
        $receiver->company_name = $request->getData('recipient_contact_company_name');
        $receiver->first_name = $request->getData('recipient_contact_person_first_name');
        $receiver->last_name = $request->getData('recipient_contact_person_last_name');
        $addressForm = new AddressForm();
        $addressForm->city = $request->getData('recipient_address_city');
        $addressForm->post_code = $request->getData('recipient_address_postal_code');
        $addressForm->country_code = $request->getData('recipient_address_country_code');
        // @todo Dodać line1 i line2 do modelu
        $addressForm->line1 = $request->getData('recipient_address_street_1');
        $addressForm->line2 = $request->getData('recipient_address_street_2');
        $receiver->address = $addressForm;
        return $receiver;
    }
}
