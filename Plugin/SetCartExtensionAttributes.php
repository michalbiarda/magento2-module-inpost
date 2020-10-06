<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Plugin;

use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Quote\Api\Data\CartSearchResultsInterface;

class SetCartExtensionAttributes
{

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $quote
     * @return CartInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        CartRepositoryInterface $subject,
        CartInterface $quote
    ): CartInterface {
        return $this->addExtensionAttributes($quote);
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $quote
     * @return CartInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetForCustomer(
        CartRepositoryInterface $subject,
        CartInterface $quote
    ): CartInterface {
        return $this->addExtensionAttributes($quote);
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartSearchResultsInterface $searchCriteria
     * @return CartSearchResultsInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(
        CartRepositoryInterface $subject,
        CartSearchResultsInterface $searchCriteria
    ): CartSearchResultsInterface {
        foreach ($searchCriteria->getItems() as $entity) {
            $this->addExtensionAttributes($entity);
        }
        return $searchCriteria;
    }

    /**
     * @param CartRepositoryInterface $subject
     * @param CartInterface $quote
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(CartRepositoryInterface $subject, CartInterface $quote)
    {
        $extensionAttributes = $quote->getExtensionAttributes();

        $quote->setData('mb_inpost_locker_name', $extensionAttributes->getMbInpostLockerName());
        $quote->setData('mb_inpost_locker_address_line_1', $extensionAttributes->getMbInpostLockerAddressLine1());
        $quote->setData('mb_inpost_locker_address_line_2', $extensionAttributes->getMbInpostLockerAddressLine2());
    }

    /**
     * @param CartInterface $quote
     * @return CartInterface
     */
    private function addExtensionAttributes(CartInterface $quote): CartInterface
    {
        $extensionAttributes = $quote->getExtensionAttributes();

        if (!$extensionAttributes->getMbInpostLockerName()) {
            $extensionAttributes->setMbInpostLockerName($quote->getData('mb_inpost_locker_name'));
        }
        if (!$extensionAttributes->getMbInpostLockerAddressLine1()) {
            $extensionAttributes->setMbInpostLockerAddressLine1($quote->getData('mb_inpost_locker_address_line_1'));
        }
        if (!$extensionAttributes->getMbInpostLockerAddressLine2()) {
            $extensionAttributes->setMbInpostLockerAddressLine2($quote->getData('mb_inpost_locker_address_line_2'));
        }

        return $quote;
    }
}
