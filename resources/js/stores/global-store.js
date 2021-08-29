import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({

    state: {

        token: sessionStorage.getItem('userToken') || localStorage.getItem('userToken') || "",
        user: sessionStorage.getItem('loggedUser') || localStorage.getItem('loggedUser') || "",
        user_id: sessionStorage.getItem('user_id') || localStorage.getItem('user_id') || "",

    },

    mutations: {

        storeRememberedUserToken(state, token) {

            this.token = token;
            localStorage.setItem('userToken', token);

        },

        storeRememberedUser(state, user, id) {
            this.user = user;
            this.user_id = id;
            localStorage.setItem('loggedUser', user);
            localStorage.setItem('user_id', id);
        },

        storeUserToken(state, token){
            this.state.token = token;
            sessionStorage.setItem('userToken', token);
        },

        storeUser(state, user, id){
            this.state.user = user;
            sessionStorage.setItem('loggedUser', user);
        },

        storeUserId(state, id){
            this.state.user_id = id;
            sessionStorage.setItem('user_id', id);
        },

        revokeUserToken(state, token) {
            this.token = " ";
            localStorage.removeItem('userToken');
            sessionStorage.removeItem('userToken');
        },

        revokeUser(state, user) {
            this.user = " ";
            this.user_id = "";
            localStorage.removeItem('user_id');
            sessionStorage.removeItem('user_id');
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
        },

        getUserId(state){
            return state.user_id;
        }

    }


});