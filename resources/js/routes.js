import App from "./App.vue";
import Cards from "./View/Cards.vue"
import Devices from "./View/Devices.vue";
import Login from "./View/login.vue";
import store from "./store";
import NotRespond from "./View/NotRespond.vue";
import Analitics from "./View/Analitics.vue";
import {createRouter, createWebHistory} from 'vue-router'

const routes = [
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: {
            middleware: "guest",
            title: `Login`,
            layout: 'Login',
        },
    },
    {
        name: 'home',
        path: '/',
        component: Devices,
        meta: {
            middleware: "auth",
            title: `Показания приборов учета`
        }
    },
    {
        name: 'cards',
        path: '/cards',
        component: Cards,
        meta: {
            middleware: "auth",
            title: `Карточки жильцов`
        }
    },
    {
        name: 'notrespond',
        path: '/notrespond',
        component: NotRespond,
        meta: {
            middleware: "auth",
            title: `Не отпраштвать`
        }
    },
    {
        name: 'analytics',
        path: '/analytics',
        component: Analitics,
        meta: {
            middleware: "auth",
            title: `Аналитика`
        }
    }
]

const router = createRouter({
    // 4. Provide the history implementation to use. We are using the hash history for simplicity here.
    history: createWebHistory(),
    routes, // short for `routes: routes`
})
router.beforeEach((to, from, next) => {
    console.log('navigator')
    console.log(store.state.auth.authenticated)
    document.title = to.meta.title
    store.dispatch('auth/checkAuth').then(() => {
        if (to.meta.middleware === "guest") {
            if (store.state.auth.authenticated) {
                next({name: "home"})
            }
            next()
        } else {
            if (store.state.auth.authenticated) {
                next()
            } else {
                next({name: "login"})
            }
        }
    })
})
export default router
