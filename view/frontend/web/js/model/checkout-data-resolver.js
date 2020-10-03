/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

define([
    'Magento_Checkout/js/model/quote',
    'MB_Inpost/js/checkout-data',
    'MB_Inpost/js/action/select-locker'
], function (quote, checkoutData, selectLockerAction) {
    'use strict';

    return {
        resolveLocker: function () {
            selectLockerAction(checkoutData.getSelectedLocker() || window.checkoutConfig.selectedMBInpostLocker);
        }
    }
});
