/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

import Vue from "vue";
import App from "./App.vue";

import Antd from "ant-design-vue";

import "ant-design-vue/dist/antd.css";

Vue.use(Antd);
const app = new Vue({
    el: "#app",
    components: { App },
    template: `<app></app>`
});
