<?php
/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace MB\Inpost\Plugin;

use Magento\Checkout\Block\Checkout\LayoutProcessor;

class CustomizeCheckoutLayout
{
    /**
     * @param LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterProcess(LayoutProcessor $subject, array $jsLayout): array
    {
        $jsLayout = $this->addLockerFieldset($jsLayout);
        return $jsLayout;
    }

    /**
     * @param array $jsLayout
     * @return array
     */
    private function addLockerFieldset(array $jsLayout): array
    {
        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['mb-inpost-locker'] = [
            'component' => 'MB_Inpost/js/view/locker',
            'displayArea' => 'shippingAdditional'
        ];
        return $jsLayout;
    }
}
