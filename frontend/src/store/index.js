import { createStore } from 'vuex';
import { login } from './login';
import { dashboard } from './dashboard';
import { password } from './password';

export default createStore({
    modules: {
        login,
        dashboard,
        password,
    }
});
