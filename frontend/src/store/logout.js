import axios from '@/axios';

export const logout = {
    namespaced: true,
    state: {
        logoutState: false,
    },
    mutations: {
        SET_LOGOUT(state) {
            state.logoutState = true;
        },
    },
    actions: {
        logout({ commit }) {
            try {
                axios.post('/logout');
                commit('SET_LOGOUT');
            } catch (error) {
                console.log('logout error');
            }
        }
    }
    ,
    getters: {
        getLogoutState: state => state.logoutState,
    }
};
