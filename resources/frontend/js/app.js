const doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

window.checkInputLabelStates = function () {
    $('.form-control').each(function () {
        const $formControl = $(this);

        $formControl.val() && $formControl.addClass("not-empty");
    });
};

$(document).ready(function () {
    let $formControls = $('.form-control');

    $formControls.on("change", function(event) {
        const $target = $(event.target);

        $target.val() ? $target.addClass("not-empty") : $target.removeClass("not-empty");
    });

    checkInputLabelStates();
});

checkInputLabelStates();

// ChartJS Stuff
Chart.defaults.global.defaultFontFamily = "'Titillium Web', sans-serif";

import Notification from '../../global/vue/Notification'
import Carousel from '../vue/Carousel'
import Price from '../vue/Catalog/Price'
import Cart from '../vue/Checkout/Cart'
import AddToCart from '../vue/Checkout/AddToCart'
import MiniCart from '../vue/Checkout/MiniCart'
import FavoritesToggleButton from '../vue/Favorites/ToggleButton'
import FooterBlock from '../vue/Footer'
import Logs from '../vue/Log'
import ContactEmail from '../vue/Account/ContactEmail'
import AddressList from '../vue/Account/AddressList'
import SubAccount from '../vue/Account/SubAccount'
import CartAddress from '../vue/Checkout/Address/CartAddress'
import QuickSearch from '../vue/Search/QuickSearch'

window.vm = new Vue({
    el: '#app',
    data () {
        return {
            skus: [],
            filter: {}
        }
    },
    components: {
        Notification,
        Carousel,
        Price,
        Cart,
        AddToCart,
        MiniCart,
        FavoritesToggleButton,
        FooterBlock,
        Logs,
        ContactEmail,
        AddressList,
        SubAccount,
        CartAddress,
        QuickSearch,
    },
    methods: {
        fetchPrices () {
            axios.post('/fetchPrices', {
                skus: this.$data.skus
            })
                .then((response) => {
                    response.data.payload.forEach((item) => {
                        this.$root.$emit('price-fetched-' + item.sku, {
                            netPrice: item.net_price,
                            grossPrice: item.gross_price,
                            pricePer: item.price_per_string,
                            stock: item.stock_string,
                            action: item.action
                        });
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    created () {
        this.$root.$on('fetch-price', (sku) => {
            this.$data.skus.push(sku);
        });
    },
    mounted () {
        if (window.Laravel.isLoggedIn && this.$data.skus.length > 0) {
            this.fetchPrices();
        }
    }
});