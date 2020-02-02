const routes = [
    {
        path: '/',
        component: () => import('../vue/pages/Dashboard'),
        meta: {
            icon: 'fa-tachometer-alt',
            name: 'Dashboard'
        }
    },
    {
        path: '/companies',
        component: () => import('../vue/pages/Companies'),
        meta: {
            icon: 'fa-users',
            name: 'Klantbeheer'
        }
    },
    {
        path: '/carousel',
        component: () => import('../vue/pages/Carousel'),
        meta: {
            icon: 'fa-image',
            name: 'Carousel'
        }
    },
    {
        path: '/catalog',
        component: () => import('../vue/pages/Catalog'),
        meta: {
            icon: 'fa-list',
            name: 'Productbeheer'
        }
    },
    {
        path: '/search-terms',
        component: () => import('../vue/pages/SearchTerms'),
        meta: {
            icon: 'fa-search',
            name: 'Zoektermen'
        }
    },
    {
        path: '/cms',
        component: () => import('../vue/pages/CMS'),
        meta: {
            icon: 'fa-pencil',
            name: 'CMS'
        }
    },
    {
        path: '/packs',
        component: () => import('../vue/pages/Packs'),
        meta: {
            icon: 'fa-box',
            name: 'Actiepaketten'
        }
    },
    {
        path: '/logs',
        component: () => import('../vue/pages/Logs'),
        meta: {
            icon: 'fa-book-open',
            name: 'Applicatie logs'
        }
    }
];

export default routes;
