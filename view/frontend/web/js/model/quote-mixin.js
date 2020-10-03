/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

define([
    'ko'
], function (ko) {
    'use strict';

    return function (quote) {
        quote.mbInpostLocker = ko.observable(null);
        return quote;
    }
});
