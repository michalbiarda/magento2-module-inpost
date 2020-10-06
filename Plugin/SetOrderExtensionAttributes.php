<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Plugin;

use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;

class SetOrderExtensionAttributes
{

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @return OrderInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ): OrderInterface {
        return $this->addExtensionAttributes($order);
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderSearchResultInterface $searchCriteria
     * @return OrderSearchResultInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $searchCriteria
    ): OrderSearchResultInterface {
        foreach ($searchCriteria->getItems() as $entity) {
            $this->addExtensionAttributes($entity);
        }
        return $searchCriteria;
    }

    /**
     * @param OrderRepositoryInterface $subject
     * @param OrderInterface $order
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(
        OrderRepositoryInterface $subject,
        OrderInterface $order
    ) {

        $extensionAttributes = $order->getExtensionAttributes();

        $order->setData('mb_inpost_locker_name', $extensionAttributes->getMbInpostLockerName());
        $order->setData('mb_inpost_locker_address_line_1', $extensionAttributes->getMbInpostLockerAddressLine1());
        $order->setData('mb_inpost_locker_address_line_2', $extensionAttributes->getMbInpostLockerAddressLine2());
    }

    /**
     * @param OrderInterface $order
     * @return OrderInterface
     */
    private function addExtensionAttributes(OrderInterface $order): OrderInterface
    {
        $extensionAttributes = $order->getExtensionAttributes();

        if (!$extensionAttributes->getMbInpostLockerName()) {
            $extensionAttributes->setMbInpostLockerName($order->getData('mb_inpost_locker_name'));
        }
        if (!$extensionAttributes->getMbInpostLockerAddressLine1()) {
            $extensionAttributes->setMbInpostLockerAddressLine1($order->getData('mb_inpost_locker_address_line_1'));
        }
        if (!$extensionAttributes->getMbInpostLockerAddressLine2()) {
            $extensionAttributes->setMbInpostLockerAddressLine2($order->getData('mb_inpost_locker_address_line_2'));
        }

        return $order;
    }
}
