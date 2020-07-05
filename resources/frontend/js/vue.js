import Vue from 'vue'
import Intersect from 'vue-intersect'
import VueRecaptcha from 'vue-recaptcha';

const Notification = () => import(/* webpackChunkName: 'vue-global-components' */ '../../global/vue/Notification');

const Carousel = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Carousel');
const Price = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Catalog/Price');
const Cart = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Checkout/Cart');
const AddToCart = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Checkout/AddToCart');
const MiniCart = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Checkout/MiniCart');
const FavoritesToggleButton = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Favorites/ToggleButton');
const FooterBlock = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Footer');
const Logs = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Log');
const ContactEmail = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Account/ContactEmail');
const AddressList = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Account/AddressList');
const SubAccount = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Account/SubAccount');
const CartAddress = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Checkout/Address/CartAddress');
const QuickSearch = () => import(/* webpackChunkName: 'vue-components' */ '../vue/Search/QuickSearch');

window.vm = new Vue({
    el: '#app',
    data() {
        return {
            showMaps: false,
            loadingPrices: false,
            skus: [],
            filter: {}
        }
    },
    components: {
        Intersect,
        VueRecaptcha,
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
        fetchPrices() {
            axios.post('/fetchPrices', {
                skus: this.$data.skus
            })
                .then((response) => {
                    response.data.payload.forEach((item) => {
                        this.$root.$emit('price-fetched-' + item.sku, {
                            netPrice: item.netPricePerUnit,
                            grossPrice: item.grossPrice,
                            pricePer: item.pricePer,
                            stock: item.stock_string,
                            action: item.actionPrice,
                            priceFactor: item.priceFactor,
                            scaleUnit: item.scaleUnit,
                            priceUnit: item.priceUnit,
                        });
                    });
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    },
    created() {
        let fetchPriceTimeout;

        this.$root.$on('fetch-price', (sku) => {
            if (!window.Laravel.isLoggedIn) {
                return;
            }

            this.$data.skus.push(sku);

            if (!this.$data.loadingPrices && fetchPriceTimeout) {
                clearTimeout(fetchPriceTimeout);
            }

            fetchPriceTimeout = setTimeout(() => {
                this.$data.loadingPrices = true;

                this.fetchPrices();
            }, 1000);
        });

        this.$root.$on('intersect-enter', (targetSelector) => {
            const target = document.querySelector(targetSelector);

            switch (target.dataset.action) {
                case 'add-class':
                    target.classList.add(target.dataset.class);
            }
        });
    },
    mounted() {
        const vueLoadedEvent = new Event('vue-loaded');

        document.body.style.opacity = "1";

        $(document).ready(function () {

            let $formControls = $('.form-control');

            $formControls.on("change", function (event) {
                const $target = $(event.target);

                $target.val() ? $target.addClass("not-empty") : $target.removeClass("not-empty");
            });

            checkInputLabelStates();
        });

        checkInputLabelStates();

        this.$emit('vue-loaded');
        window.dispatchEvent(vueLoadedEvent);
    }
});
