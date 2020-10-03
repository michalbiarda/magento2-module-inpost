/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

define([
    'Magento_Checkout/js/model/quote',
    'mage/utils/wrapper'
], function (quote, wrapper) {
    'use strict';

    return function (payloadExtender) {
        payloadExtender = wrapper.wrap(
            payloadExtender,
            function (originalFunction, payload) {
                payload = originalFunction(payload);
                var locker = quote.mbInpostLocker();
                if (locker) {
                    payload.addressInformation['extension_attributes']['mb_inpost_locker_name'] = locker.name;
                    payload.addressInformation['extension_attributes']['mb_inpost_locker_address_line_1']
                        = locker.address.line1;
                    payload.addressInformation['extension_attributes']['mb_inpost_locker_address_line_2']
                        = locker.address.line2;
                }
                return payload;
            }
        );
        return payloadExtender;
    };
});
