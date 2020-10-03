<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Plugin;

use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\ResourceModel\Order as OrderResourceModel;

class AddLockerDataToShippingDescriptionBeforeSave
{
    /**
     * @param OrderResourceModel $subject
     * @param Order $order
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(OrderResourceModel $subject, Order $order): array
    {
        if (!$order->getId() && $order->getShippingMethod() === 'mb_inpost_locker_standard') {
            $extensionAttributes = $order->getExtensionAttributes();
            $shippingDescription = $order->getShippingDescription()
                . ' ('
                . $this->getLockerName($extensionAttributes, $order) . ', '
                . $this->getLockerAddressLine1($extensionAttributes, $order) . ', '
                . $this->getLockerAddressLine2($extensionAttributes, $order)
                . ')';
            $order->setShippingDescription($shippingDescription);
        }
        return [$order];
    }

    /**
     * @param OrderExtensionInterface|null $extensionAttributes
     * @param Order $order
     * @return string
     */
    private function getLockerName(?OrderExtensionInterface $extensionAttributes, Order $order): string
    {
        return (string) ($extensionAttributes && $extensionAttributes->getMbInpostLockerName())
            ? $extensionAttributes->getMbInpostLockerName()
            : $order->getData('mb_inpost_locker_name');
    }

    /**
     * @param OrderExtensionInterface|null $extensionAttributes
     * @param Order $order
     * @return string
     */
    private function getLockerAddressLine1(?OrderExtensionInterface $extensionAttributes, Order $order): string
    {
        return (string) ($extensionAttributes && $extensionAttributes->getMbInpostLockerAddressLine1())
            ? $extensionAttributes->getMbInpostLockerAddressLine1()
            : $order->getData('mb_inpost_locker_address_line_1');
    }

    /**
     * @param OrderExtensionInterface|null $extensionAttributes
     * @param Order $order
     * @return string
     */
    private function getLockerAddressLine2(?OrderExtensionInterface $extensionAttributes, Order $order): string
    {
        return (string) ($extensionAttributes && $extensionAttributes->getMbInpostLockerAddressLine2())
            ? $extensionAttributes->getMbInpostLockerAddressLine2()
            : $order->getData('mb_inpost_locker_address_line_2');
    }
}
