/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'Magento_Checkout/js/model/quote'
], function ($, quote) {
    'use strict';

    return function (billingAddress, putShippingAddress = false) {
        var address = null;
        if (quote.shippingAddress() && billingAddress.getCacheKey() == //eslint-disable-line eqeqeq
            quote.shippingAddress().getCacheKey()
        ) {
            address = $.extend({}, billingAddress);
            address.saveInAddressBook = null;
        } else if (putShippingAddress) {
            address = quote.shippingAddress();
        } else {
            address = billingAddress;
        }
        quote.billingAddress(address);
    };
});
