<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Plugin;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Api\ShippingInformationManagementInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\CartRepositoryInterface;
use MB\Inpost\Model\LockerValidator;

class SetLockerDataOnCart
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var LockerValidator
     */
    private $lockerValidator;

    /**
     * @param CartRepositoryInterface $cartRepository
     * @param LockerValidator $lockerValidator
     */
    public function __construct(
        CartRepositoryInterface $cartRepository,
        LockerValidator $lockerValidator
    ) {
        $this->cartRepository = $cartRepository;
        $this->lockerValidator = $lockerValidator;
    }

    /**
     * @param ShippingInformationManagementInterface $subject
     * @param $cartId
     * @param ShippingInformationInterface $addressInformation
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSaveAddressInformation(
        ShippingInformationManagementInterface $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ): void {
        if ($this->isLockerMethodSelected($addressInformation)) {
            $addressExtensionAttributes = $addressInformation->getExtensionAttributes();
            if ($this->lockerValidator->validate($addressExtensionAttributes)) {
                $cartExtensionAttributes = $this->cartRepository->getActive($cartId)->getExtensionAttributes();
                $cartExtensionAttributes
                    ->setMbInpostLockerName($addressExtensionAttributes->getMbInpostLockerName());
                $cartExtensionAttributes
                    ->setMbInpostLockerAddressLine1($addressExtensionAttributes->getMbInpostLockerAddressLine1());
                $cartExtensionAttributes
                    ->setMbInpostLockerAddressLine2($addressExtensionAttributes->getMbInpostLockerAddressLine2());
            }
        }
    }

    /**
     * @param  ShippingInformationInterface $addressInformation
     * @return bool
     */
    private function isLockerMethodSelected(ShippingInformationInterface $addressInformation): bool
    {
        return $addressInformation->getShippingCarrierCode() === 'mb_inpost' &&
            $addressInformation->getShippingMethodCode() === 'locker_standard';
    }
}
