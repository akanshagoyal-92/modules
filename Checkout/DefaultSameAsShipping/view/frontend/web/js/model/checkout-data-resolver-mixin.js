define([
    'Magento_Customer/js/model/address-list',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/checkout-data',
    'Magento_Checkout/js/action/create-shipping-address',
    'Magento_Checkout/js/action/select-shipping-address',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/model/payment-service',
    'Magento_Checkout/js/action/select-payment-method',
    'Magento_Checkout/js/model/address-converter',
    'Magento_Checkout/js/action/select-billing-address',
    'Magento_Checkout/js/action/create-billing-address',
    'underscore'
], function (
    addressList,
    quote,
    checkoutData,
    createShippingAddress,
    selectShippingAddress,
    selectShippingMethodAction,
    paymentService,
    selectPaymentMethodAction,
    addressConverter,
    selectBillingAddress,
    createBillingAddress,
    _
) {
	'use strict';
	var isBillingAddressResolvedFromBackend = false;
	return function(checkoutResolver) {

			checkoutResolver.resolveBillingAddress = function () {
	            var selectedBillingAddress,
	                newCustomerBillingAddressData;

	            selectedBillingAddress = checkoutData.getSelectedBillingAddress();
	            newCustomerBillingAddressData = checkoutData.getNewCustomerBillingAddress();

	            if (selectedBillingAddress === null && newCustomerBillingAddressData === null){
	                var putShippingAddress = true;
	            } else {
	                var putShippingAddress = false;
	            }

	            if (selectedBillingAddress) {
	                if (selectedBillingAddress === 'new-customer-billing-address' && newCustomerBillingAddressData) {
	                    selectBillingAddress(createBillingAddress(newCustomerBillingAddressData), putShippingAddress);
	                } else {
	                    addressList.some(function (address) {
	                        if (selectedBillingAddress === address.getKey()) {
	                            selectBillingAddress(address, putShippingAddress);
	                        }
	                    });
	                }
	            } else {
	                this.applyBillingAddress();
	            }

	            if (!isBillingAddressResolvedFromBackend &&
	                !checkoutData.getBillingAddressFromData() &&
	                !_.isEmpty(window.checkoutConfig.billingAddressFromData) &&
	                !quote.billingAddress()
	            ) {
	                if (window.checkoutConfig.isBillingAddressFromDataValid === true) {
	                    selectBillingAddress(createBillingAddress(window.checkoutConfig.billingAddressFromData));
	                } else {
	                    checkoutData.setBillingAddressFromData(window.checkoutConfig.billingAddressFromData);
	                }
	                isBillingAddressResolvedFromBackend = true;
	            }
	        };

			checkoutResolver.applyBillingAddress = function () {
	            var shippingAddress,
	                isBillingAddressInitialized;

	            shippingAddress = quote.shippingAddress();

	            if (shippingAddress &&
	                shippingAddress.canUseForBilling() &&
	                (shippingAddress.isDefaultShipping() || !quote.isVirtual())
	            ) {
	                selectBillingAddress(quote.shippingAddress());
	            	return true;
	            }

	            if (quote.isVirtual() || !quote.billingAddress()) {
	                isBillingAddressInitialized = addressList.some(function (addrs) {
	                    if (addrs.isDefaultBilling()) {
	                        selectBillingAddress(addrs);
	                        return true;
	                    }
	                    return false;
	                });
	            }
	        }

	        return checkoutResolver;
        }
    }
);