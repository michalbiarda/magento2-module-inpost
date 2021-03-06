/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

define([
    'Magento_Checkout/js/model/quote',
    'mage/translate'
], function (quote, $t) {
    'use strict';

    return function (shipping) {
        return shipping.extend({
            initialize: function () {
                this._super();
                var self = this;
                quote.mbInpostLocker.subscribe(
                    function () {
                        self.errorValidationMessage(false);
                    }
                );
            },
            validateShippingInformation: function () {
                if (quote.shippingMethod()
                    && quote.shippingMethod().carrier_code === 'mbinpost'
                    && quote.shippingMethod().method_code === 'locker_standard'
                    && !quote.mbInpostLocker()
                ) {
                    this.errorValidationMessage(
                        $t('You need to choose a locker.')
                    );
                    return false;
                }
                return this._super();
            }
        });
    };
});
