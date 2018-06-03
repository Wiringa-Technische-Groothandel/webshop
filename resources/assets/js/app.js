require('./bootstrap');

var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

// ChartJS Stuff
Chart.defaults.global.defaultFontFamily = "'Titillium Web', sans-serif";

Vue.component('carousel', require('./components/Carousel'));
Vue.component('price', require('./components/Catalog/Price'));
Vue.component('cart', require('./components/Checkout/Cart'));
Vue.component('add-to-cart', require('./components/Checkout/AddToCart'));
Vue.component('mini-cart', require('./components/Checkout/MiniCart'));
Vue.component('favorites-toggle-button', require('./components/Favorites/ToggleButton'));
Vue.component('notification', require('./components/Notification'));
Vue.component('footer-block', require('./components/Footer'));
Vue.component('logs', require('./components/Log'));
Vue.component('contact-email', require('./components/Account/ContactEmail'));
Vue.component('address-list', require('./components/Account/AddressList'));
Vue.component('cart-address', require('./components/Checkout/Address/CartAddress'));
Vue.component('quick-search', require('./components/Search/QuickSearch'));

import 'vue-googlemaps/dist/vue-googlemaps.css'
import VueGoogleMaps from 'vue-googlemaps'

Vue.use(VueGoogleMaps, {
    load: {
        apiKey: window.Laravel.googleMapsApiKey,
        libraries: ['places'],
    },
});

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