import {get, set} from 'idb-keyval';

export default {
    namespaced: true,
    state: {
        products: []
    },
    getters: {
        async hasProducts (state, getters) {
            return await getters['getProducts'].then(products => {
                return !!products.length;
            });
        },
        async getProducts (state) {
            if (! state.products.length) {
                return await get('products').then(val => {
                    if (! val) {
                        return [];
                    }

                    state.products = val;

                    return val;
                });
            }

            return state.products;
        }
    },
    mutations: {
        products (state, { products }) {
            set('products', products)
                .then(() => {
                    state.products = products;
                });
        }
    }
}