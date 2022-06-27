var config = {
    map: {
        '*': {
            'Magento_Checkout/js/action/select-billing-address':'Checkout_DefaultSameAsShipping/js/action/select-billing-address',
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/model/checkout-data-resolver': {
                'Checkout_DefaultSameAsShipping/js/model/checkout-data-resolver-mixin': true
            },
        }
    }
};