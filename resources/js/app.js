import 'bootstrap'; 
import 'bootstrap/dist/css/bootstrap.min.css';

import axios from 'axios';
Vue.prototype.$axios = axios;

import Vue from 'vue';
window.Vue = Vue;

import VueRouter from 'vue-router';
Vue.use(VueRouter);

import Toasted from 'vue-toasted';
Vue.use(Toasted, {

    position: 'top-right',
    duration: '3000',
    type: 'info'

});

import AuctionsComponent from './components/auctions.vue';
import RegisterComponent from './components/register.vue';
import AlertBoxComponent from './components/alertBox.vue';
import LoginComponent from './components/login.vue';
import CreateAuctionComponent from './components/createAuction.vue';
import MyAuctionsComponent from './components/myAuctions.vue';
import NavBarComponent from './components/navbar.vue';
import ForgotPasswordComponent from './components/forgotPassword.vue';

const auctions = Vue.component("auctions", AuctionsComponent);
const register = Vue.component("register", RegisterComponent);
const alertBox = Vue.component("alertBox", AlertBoxComponent);
const login = Vue.component("login", LoginComponent);
const createAuction = Vue.component("create-auction", CreateAuctionComponent);
const myAuctions = Vue.component("myAuctions", MyAuctionsComponent);
const navbar = Vue.component("navbar", NavBarComponent);
const forgotPassword = Vue.component("forgotPassowrd", ForgotPasswordComponent);

const routes = [

    { path: '/', component: auctions },
    { path: '/auctions', component: auctions},
    { path: '/register', component: register },
    { path: '/login', component: login },
    { path: '/create-auction', component: createAuction},
    { path: '/my-auctions', component: myAuctions},
    { path: '/forgotPassword', component: forgotPassword},

];

const router = new VueRouter({
    routes: routes,
});

import store from './stores/global-store';

const app = new Vue({
    router,
    store,
}).$mount('#app');