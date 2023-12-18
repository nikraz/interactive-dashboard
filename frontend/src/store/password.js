import axios from '@/axios';

export const password = {
    namespaced: true,
    state: {
        passwordStatus: ''
    },
    mutations: {
        SET_STATUS(state, status) {
            state.passwordStatus = status;
        },
    },
    actions: {
        async resetPasswordEmail({ commit }, email) {
            try {
                const response = await axios.post('/password/email', email);
                commit('SET_STATUS', response.data);
            } catch (error) {
                commit('SET_STATUS', error.status);
            }
        }
    },
    getters: {
        passwordStatus: state => state.passwordStatus,
    }
};
