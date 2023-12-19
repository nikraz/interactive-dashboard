import { createStore } from 'vuex';
import { login } from './login';
import { logout } from './logout';
import { dashboard } from './dashboard';
import { password } from './password';
import { emailVerification } from './emailVerification';

export default createStore({
    modules: {
        login,
        dashboard,
        password,
        logout,
        emailVerification,
    }
});
