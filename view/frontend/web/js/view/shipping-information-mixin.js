/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

define([
    'Magento_Checkout/js/model/quote'
], function (quote) {
    'use strict';

    return function (shippingInformation) {
        return shippingInformation.extend({
            getShippingMethodTitle: function () {
                var title = this._super();
                if (quote.shippingMethod()
                    && quote.shippingMethod().carrier_code === 'mbinpost'
                    && quote.shippingMethod().method_code === 'locker_standard'
                    && quote.mbInpostLocker()
                ) {
                    title = title + ' (' + quote.mbInpostLocker().name + ', ' + quote.mbInpostLocker().address.line1
                    + ', ' + quote.mbInpostLocker().address.line2 + ')';
                }
                return title;
            }
        });
    };
});
