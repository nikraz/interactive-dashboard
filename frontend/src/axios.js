import axios from 'axios';
import store from '@/store'; // Import your Vuex store

const baseURL = process.env.VUE_APP_API_URL || 'http://localhost:8000';

const instance = axios.create({
    baseURL: baseURL
});

// Add a request interceptor
instance.interceptors.request.use(function (config) {
    const token = store.state.login.token;

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
}, function (error) {
    return Promise.reject(error);
});

export default instance;
