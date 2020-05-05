/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

// require('./bootstrap');


window._ = require('lodash');
try {
    window.$ = window.jQuery = require('jquery');
    require('foundation-sites');
} catch (e) {

}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

import Vue from 'vue';
import router from "./routes";
import store from "./store";
//将 Vue 挂载到 ID 为 app 的页面元素上
new Vue({
    router,
    store,
}).$mount('#app');


//我们在每次路由更新后，都会模拟向 Google Analytics 发送页面访问请求并设置访问的页面链接，
// 这样我们就可以在 Google Analytics 查看访问统计信息了：
ga('set', 'page', router.currentRoute.path);
ga('send', 'pageview');

router.afterEach((to, from) => {
    ga('set', 'page', to.path);
    ga('send', 'pageview');
});
