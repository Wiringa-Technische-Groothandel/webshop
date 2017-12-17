
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// $(document).snowfall({ flakeCount : 100, shadow: true, round: true, maxSize: 5 });

var doc = document.documentElement;
doc.setAttribute('data-useragent', navigator.userAgent);

// ChartJS Stuff
Chart.defaults.global.defaultFontFamily = "'Titillium Web', sans-serif";

import Notification from './components/NotificationComponent'
import Footer from './components/FooterComponent'
import Logs from './components/LogComponent'

import Price from './components/Catalog/PriceComponent'

import Cart from './components/Checkout/CartComponent'
import AddToCart from './components/Checkout/AddToCartComponent'
import MiniCart from './components/Checkout/MiniCartComponent'

import FavoritesButton from './components/Favorites/ButtonComponent'

import 'vue-googlemaps/dist/vue-googlemaps.css'
import VueGoogleMaps from 'vue-googlemaps'

Vue.use(VueGoogleMaps, {
    load: {
        apiKey: 'AIzaSyAQ1vHHgU-naArhlFsQOZKcy_Lp3yPHh7Y',
        libraries: ['places'],
    },
});

window.vm = new Vue({
    props: ['loggedIn'],
    el: '#app',
    components: {
        'price': Price,
        'cart': Cart,
        'add-to-cart': AddToCart,
        'mini-cart': MiniCart,
        'favorites-button': FavoritesButton,
        'notification': Notification,
        'footer-block': Footer,
        'logs': Logs
    },
    data () {
        return {
            skus: []
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
                            pricePer: item.price_per_string
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
        if (window.Laravel.isLoggedIn) {
            this.fetchPrices();
        }
    }
});