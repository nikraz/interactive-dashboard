import { createStore } from 'vuex';
import { login } from './login';
import { dashboard } from './dashboard';

export default createStore({
    modules: {
        login,
        dashboard,
    }
});
