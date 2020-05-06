//文件包含所有 Roast 单页面应用的前端路由
//可以在这里定义 Vue Router 使用的路由。

/*
 |-------------------------------------------------------------------------------
 | routes.js
 |-------------------------------------------------------------------------------
 | Contains all of the routes for the application
 */

/**
 * Imports Vue and VueRouter to extend with the routes.
 */

import Vue from 'vue';
import VueRouter from 'vue-router';

import Layout from "./pages/Layout";
import Home from "./pages/Home";
import Cafes from "./pages/Cafes";
import Cafe from "./pages/Cafe";
import NewCafe from "./pages/NewCafe";
import store from './store.js';

import Profile from "./pages/Profile";

/**
 * Extends Vue to use Vue Router
 */
Vue.use(VueRouter);

function requireAuth(to, from, next) {
    function proceed() {
        // 如果用户信息已经加载并且不为空则说明该用户已登录，可以继续访问路由，否则跳转到首页
        // 这个功能类似 Laravel 中的 auth 中间件
        if (store.getters.getUserLoadStatus() === 2) {
            if (store.getters.getUser !== '') {
                next();
            } else {
                next('/home');
            }
        }
    }

    if (store.getters.getUserLoadStatus() !== 2) {
        // 如果用户信息未加载完毕则先加载
        store.dispatch('loadUser');

        // 监听用户信息加载状态，加载完成后调用 proceed 方法继续后续操作
        store.watch(store.getters.getUserLoadStatus, function () {
            if (store.getters.getUserLoadStatus() === 2) {
                proceed();
            }
        });
    } else {
        // 如果用户信息加载完毕直接调用 proceed 方法
        proceed()
    }
}

const routes = [
    {
        path: '/',
        redirect: {name: 'home'},
        name: 'layout',
        component: Layout,
        children: [
            {
                path: 'home',
                name: 'home',
                component: Home,
            },
            {
                path: '/cafes',
                name: 'cafes',
                component: Cafes,
            },
            {
                path: '/cafes/new',
                name: 'newcafe',
                component: NewCafe,
                beforeEnter: requireAuth,
            },
            {
                path: '/cafes/:id', //动态路由,可以通过传入指定 ID 参数来加载对应的咖啡店详情
                name: 'cafe',
                component: Cafe,
            },
            {
                path: 'profile',
                name: 'profile',
                component: Profile,
                beforeEnter: requireAuth,
            }
        ]
    }
];
const router = new VueRouter({
    mode: 'history',
    routes,
});
export default router;
