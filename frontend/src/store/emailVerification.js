import axios from '@/axios';

export const emailVerification = {
    namespaced: true,
    state: {
        emailSent: false,
    },
    mutations: {
        SET_EMAIL_SENT(state) {
            state.emailSent = true;
        },
    },
    actions: {
        sendEmailVerification({ commit }) {
            try {
                axios.post('/email/validate/send');
                commit('SET_EMAIL_SENT');
            } catch (error) {
                console.log('send verification email error');
            }
        }
    }
};
