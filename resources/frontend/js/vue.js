const Intersect = () => import(/* webpackChunkName: 'vue-components' */ 'vue-intersect');
const Notification = () => import(/* webpackChunkName: 'vue-components' */ '../../global/vue/Notification');
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
    data () {
        return {
            showMaps: false,
            loadingPrices: false,
            skus: [],
            filter: {}
        }
    },
    components: {
        Intersect,
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
        let fetchPriceTimeout;

        this.$root.$on('fetch-price', (sku) => {
            if (! window.Laravel.isLoggedIn) {
                return;
            }

            this.$data.skus.push(sku);

            if (! this.$data.loadingPrices && fetchPriceTimeout) {
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
        })
    }
});
