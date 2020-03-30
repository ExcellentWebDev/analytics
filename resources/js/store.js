import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import router from './router';
Vue.use(Vuex)
var Axios = axios.create({
    // baseURL: 'http://127.0.0.1:7000/api',
    baseURL: 'http://analytics.quiktix.co/api',
    headers: {
        'Content-Type': 'application/json'
    }
});
Axios.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    config.headers.Authorization = token ? `Bearer ${token}` : '';
    return config;
})
Axios.interceptors.response.use(function (response) {
    return response
}, function (error) {
    const { config, response: { status } } = error;
    
    if (status === 401) {
        let currPath = router.currentRoute.path;
        if (currPath !== "/login" || currPath !== "/") {
            store.dispatch("REFRESH_TOKEN")
        } else {
            localStorage.clear();
            return Promise.reject(error)
        }
    } else {
        return Promise.reject(error)
    }
})
const store = new Vuex.Store({
    state: {
        role: null,
        token: null,
        q: []
    },
    mutations: {
        UPDATE_QUESTIONS_HANDLE(state, payload) {
            state.q = payload
        }
    },
    actions: {
        LOGIN_HANDLE({state, commit}, payload) {
            return new Promise((resolve, reject) => {
                Axios.get('/login', {params:payload}).then(res => {
                    localStorage.setItem('email', res.data.email);
                    localStorage.setItem('role',  res.data.role);
                    localStorage.setItem('token', res.data.access_token);
                    state.token = res.data.access_token;
                    resolve(res.data)
                }, err => {
                    reject(err)
                });
            })
        },
        REFRESH_TOKEN({state, commit}) {
            return new Promise((resolve, reject) => {
                let params = {
                    user_email: localStorage.getItem("email"),
                    license: localStorage.getItem("license")
                }
                Axios.get('/refresh_token', {params}).then(res => {
                    localStorage.setItem('token', res.data);
                    state.token = res.data;
                    resolve(res.data)
                }, err => {
                    reject(err)
                });
            })
        },
        LOGOUT_HANDLE({state, commit}, payload) {
            return new Promise((resolve, reject) => {
                Axios.get('/logout').then(res => {
                    localStorage.setItem('role', "");
                    localStorage.setItem('token', "");
                    state.user = "";
                    state.role = "";
                    router.push("/login");
                    resolve(res.data);
                }, err => {
                    reject(err)
                })
            })
        },
        //license management
        GET_LICENSES({state, commit}) {
            return new Promise((resolve, reject) => {
                Axios.get('/get_license').then(res => {
                    resolve(res.data);
                }, err => {
                    reject(err)
                })
            })
        },
        DELETE_LICENSE({state, commit}, payload) {
            return new Promise((resolve, reject) => {
                Axios.post('/delete_license', payload).then(res => {
                    resolve(res.data);
                }, err => {
                    reject(err)
                })
            })
        },
        CREATE_LICENSE({state, commit}, payload) {
            return new Promise((resolve, reject) => {
                Axios.post('/create_license', payload).then(res => {
                    resolve(res.data);
                }, err => {
                    reject(err)
                })
            })
        },
        RENEW_LICENSE({state, commit}, payload) {
            return new Promise((resolve, reject) => {
                Axios.post('/renew_license', payload).then(res => {
                    resolve(res.data);
                }, err => {
                    reject(err)
                })
            })
        },
        //search events
        SEARCH_EVENTS({state, commit}, payload) {
            return new Promise((resolve, reject) => {
                Axios.get('/search_events', payload).then(res => {
                    resolve(res.data);
                }, err => {
                    reject(err)
                })
            })
        },
        
    }
})
export default store;