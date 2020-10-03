/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

define([
    'mage/utils/wrapper',
    'MB_Inpost/js/checkout-data'
], function (wrapper, checkoutData) {
    'use strict';

    return function (targetFunction) {
        return wrapper.wrap(targetFunction, function (originalFunction, ...args) {
            var result = originalFunction(...args);
            result.success(function (response) {
                if (response.responseType !== 'error') {
                    checkoutData.clearStorage();
                }
            });
            return result;
        });
    };
});
