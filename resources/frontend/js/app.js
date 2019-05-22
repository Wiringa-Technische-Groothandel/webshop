var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

// ChartJS Stuff
Chart.defaults.global.defaultFontFamily = "'Titillium Web', sans-serif";

Vue.component('notification', require('../../global/vue/Notification'));

Vue.component('carousel', require('../vue/Carousel'));
Vue.component('price', require('../vue/Catalog/Price'));
Vue.component('cart', require('../vue/Checkout/Cart'));
Vue.component('add-to-cart', require('../vue/Checkout/AddToCart'));
Vue.component('mini-cart', require('../vue/Checkout/MiniCart'));
Vue.component('favorites-toggle-button', require('../vue/Favorites/ToggleButton'));
Vue.component('footer-block', require('../vue/Footer'));
Vue.component('logs', require('../vue/Log'));
Vue.component('contact-email', require('../vue/Account/ContactEmail'));
Vue.component('address-list', require('../vue/Account/AddressList'));
Vue.component('sub-account', require('../vue/Account/SubAccount'));
Vue.component('cart-address', require('../vue/Checkout/Address/CartAddress'));
Vue.component('quick-search', require('../vue/Search/QuickSearch'));

window.vm = new Vue({
    el: '#app',
    data () {
        return {
            skus: [],
            filter: {}
        }
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