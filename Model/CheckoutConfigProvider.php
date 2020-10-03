<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CheckoutConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Session
     */
    private $checkoutSession;

    /**
     * @param Session $checkoutSession
     */
    public function __construct(Session $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getConfig(): array
    {
        $extensionAttributes = $this->checkoutSession->getQuote()->getExtensionAttributes();
        $name = $extensionAttributes->getMbInpostLockerName();
        $addressLine1 = $extensionAttributes->getMbInpostLockerAddressLine1();
        $addressLine2 = $extensionAttributes->getMbInpostLockerAddressLine2();
        if ($name && $addressLine1 && $addressLine2) {
            $selectedLocker = [
                'name' => $name,
                'address' => [
                    'line1' => $addressLine1,
                    'line2' => $addressLine2
                ]
            ];
        } else {
            $selectedLocker = null;
        }
        return [
            'selectedMBInpostLocker' => $selectedLocker
        ];
    }
}
