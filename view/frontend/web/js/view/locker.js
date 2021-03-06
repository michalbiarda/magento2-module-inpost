/**
 * Copyright © Michał Biarda. All rights reserved.
 * See LICENSE.txt for license details.
 */

define([
    'ko',
    'jquery',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Ui/js/modal/modal',
    'mage/translate',
    'mage/url',
    'MB_Inpost/js/action/select-locker',
    'MB_Inpost/js/checkout-data',
    'MB_Inpost/js/model/checkout-data-resolver'
], function (
    ko,
    $,
    Component,
    quote,
    modal,
    $t,
    url,
    selectLockerAction,
    inpostCheckoutData,
    inpostCheckoutDataResolver
) {
    'use strict';

    var lockerMethodCode = 'mbinpost_locker_standard';

    return Component.extend({
        defaults: {
            template: 'MB_Inpost/checkout/locker'
        },
        isVisible: ko.observable(false),
        selectedLocker: ko.observable(null),
        initialize: function () {
            this._super();
            quote.shippingMethod.subscribe(this.shippingMethodCallback.bind(this));
            quote.mbInpostLocker.subscribe(this.lockerCallback.bind(this));
            inpostCheckoutDataResolver.resolveLocker();
            this.addMessageListener();
        },
        shippingMethodCallback: function (shippingMethod) {
            if (shippingMethod) {
                this.isVisible(shippingMethod.carrier_code + '_'
                + shippingMethod.method_code === lockerMethodCode);
            } else {
                this.isVisible(false);
            }
        },
        lockerCallback: function (locker) {
            this.selectedLocker(locker);
        },
        getIframeSrc: function () {
            return url.build('mbinpost/locker/map');
        },
        initModal: function () {
            modal(
                {
                    type: 'popup',
                    responsive: true,
                    innerScroll: true,
                    title: $t('Choose locker'),
                    buttons: [],
                    modalClass: 'mb-inpost-modal-choose-locker'
                },
                $('#locker-modal-content')
            );
        },
        openModal: function () {
            $('#locker-modal-content').modal('openModal');
        },
        closeModal: function () {
            $('#locker-modal-content').modal('closeModal');
        },
        addMessageListener: function () {
            var self = this;
            window.addEventListener(
                'message',
                function (event) {
                    if (self.isMessageValid(event)) {
                        self.selectLocker(event.data.mb_inpost_locker);
                        self.closeModal();
                    }
                },
                false
            );
        },
        isMessageValid(event) {
            return event.origin === url.build('').slice(0, -1)
            && typeof event.data.mb_inpost_locker === 'object';
        },
        selectLocker: function (locker) {
            selectLockerAction(locker);
            inpostCheckoutData.setSelectedLocker(locker);
        },
        getSelectedLockerLabel: function () {
            var selectedLocker = this.selectedLocker();
            return selectedLocker.name + ', ' + selectedLocker.address.line1 + ', '
            + selectedLocker.address.line2;
        }
    });
});
