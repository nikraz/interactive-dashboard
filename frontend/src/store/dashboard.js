import axios from '@/axios';

export const dashboard = {
    namespaced: true,
    state: {
        dashboardDataPrices: {},
        dashboardDataLastUpdate: {},
    },
    mutations: {
        SET_DASHBOARD_DATA(state, dashboardData) {
            state.dashboardDataPrices = dashboardData;
        },
        CLEAR_DASHBOARD_DATA(state) {
            state.dashboardDataPrices = {};
        },
        SET_DASHBOARD_LAST_UPDATE_DATA(state, dashboardDataLastUpdate) {
            state.dashboardDataLastUpdate = dashboardDataLastUpdate;
        },
        CLEAR_DASHBOARD_LAST_UPDATE_DATA(state) {
            state.dashboardDataLastUpdate = {};
        }
    },
    actions: {
        async getDashboardData({ commit }) {
            try {
                const response = await axios.get('/market/last-update');
                commit('SET_DASHBOARD_DATA', response.data.prices);
                commit('SET_DASHBOARD_LAST_UPDATE_DATA', response.data.last_update);
            } catch (error) {
                commit('CLEAR_DASHBOARD_DATA');
                commit('CLEAR_DASHBOARD_LAST_UPDATE_DATA');
            }
        }
    },
    getters: {
        dashboardDataPrices: state => state.dashboardDataPrices,
        dashboardDataLastUpdate: state => state.dashboardDataLastUpdate,
    }
};
