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

/**
 * Extends Vue to use Vue Router
 */
Vue.use(VueRouter);

const routes = [
    {
        path: '/',
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
            },
            {
                path: '/cafes/:id', //动态路由,可以通过传入指定 ID 参数来加载对应的咖啡店详情
                name: 'cafe',
                component: Cafe,
            }
        ]
    }
];
const router = new VueRouter({
    // mode: 'history',
    routes,
});
export default router;
