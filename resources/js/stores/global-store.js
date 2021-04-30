import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({

    state: {

        token: sessionStorage.getItem('userToken') || localStorage.getItem('userToken') || "",
        user: sessionStorage.getItem('loggedUser') || localStorage.getItem('loggedUser') || "",

    },

    mutations: {

        storeRememberedUserToken(state, token) {

            this.token = token;
            localStorage.setItem('userToken', token);

        },

        storeRememberedUser(state, user) {
            this.user = user;
            localStorage.setItem('loggedUser', user);
        },

        storeUserToken(state, token){
            this.state.token = token;
            sessionStorage.setItem('userToken', token);
        },

        storeUser(state, user){
            this.state.user = user;
            sessionStorage.setItem('loggedUser', user);
        },

        revokeUserToken(state, token) {
            this.token = " ";
            localStorage.removeItem('userToken');
            sessionStorage.removeItem('userToken');
        },

        revokeUser(state, user) {
            this.user = " ";
            localStorage.removeItem('loggedUser');
            sessionStorage.removeItem('loggedUser');
        },

    },

    getters: {

        getUserToken(state){
            return state.token;
        },

        getUser(state){
            return state.user;
        }

    }


});