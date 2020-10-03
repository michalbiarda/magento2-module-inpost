/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

define([
    'jquery',
    'Magento_Customer/js/customer-data',
    'jquery/jquery-storageapi'
], function ($, storage) {
    'use strict';

    var cacheKey = 'mb-inpost-checkout-data',

    saveData = function (data) {
        storage.set(cacheKey, data);
    },

    initData = function () {
        return {
            'selectedLocker': null
        };
    },

    getData = function () {
        var data = storage.get(cacheKey)();

        if ($.isEmptyObject(data)) {
            data = $.initNamespaceStorage('mage-cache-storage').localStorage.get(cacheKey);

            if ($.isEmptyObject(data)) {
                data = initData();
                saveData(data);
            }
        }

        return data;
    };

    return {
        setSelectedLocker: function (data) {
            var obj = getData();

            obj.selectedLocker = data;
            saveData(obj);
        },
        getSelectedLocker: function () {
            return getData().selectedLocker;
        },
        clearStorage: function () {
            saveData(initData());
        }
    };
});
