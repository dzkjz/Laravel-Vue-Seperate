/*
 |-------------------------------------------------------------------------------
 | VUEX store.js
 |-------------------------------------------------------------------------------
 | Builds the data store from all of the modules for the Roast app.
 | Vuex 模块的起点
 | Vuex 由一个父模块和多个子模块构成，本文件包含父模块，
 | 随后我们会导入所有子模块到这个文件。
 */
/**
 * Import Vue and Vuex
 */
import Vue from 'vue';
import Vuex from 'vuex';

/**
 * Adds the promise polyfill for IE 11
 */
require('es6-promise').polyfill();
/**
 * Initializes Vuex on Vue.
 */
Vue.use(Vuex);

/**
 * Imports all of the modules used in the application to build the data store.
 */
import {cafes} from "./modules/cafes";

import {brewMethods} from "./modules/brewMethods";

import {users} from "./modules/users";

/**
 * Export the data store.
 */
const store = new Vuex.Store({
    modules: {
        cafes,
        brewMethods,
        users,
    },
});

export default store;
