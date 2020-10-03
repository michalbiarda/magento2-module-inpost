/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

var config = {
    "config": {
        "mixins": {
            "Magento_Checkout/js/model/shipping-save-processor/payload-extender": {
                "MB_Inpost/js/model/shipping-save-processor/payload-extender-mixin": true
            },
            "Magento_Checkout/js/model/quote": {
                "MB_Inpost/js/model/quote-mixin": true
            },
            "Magento_Checkout/js/view/shipping": {
                "MB_Inpost/js/view/shipping-mixin": true
            },
            "Magento_Checkout/js/view/shipping-information": {
                "MB_Inpost/js/view/shipping-information-mixin": true
            },
            "Magento_Checkout/js/model/place-order": {
                "MB_Inpost/js/model/place-order-mixin": true
            }
        }
    }
}