import axios from '@/axios';

export const login = {
    namespaced: true,
    state: {
        isAuthenticated: false,
        token: ''
    },
    mutations: {
        SET_AUTH(state, token) {
            state.isAuthenticated = true;
            state.token = token;
        },
        CLEAR_AUTH(state) {
            state.isAuthenticated = false;
            state.token = '';
        }
    },
    actions: {
        async login({ commit }, credentials) {
            try {
                const response = await axios.post('/login', credentials);
                commit('SET_AUTH', response.data.token);
            } catch (error) {
                commit('CLEAR_AUTH');
            }
        }
    },
    getters: {
        isAuthenticated: state => state.isAuthenticated,
    }
};
