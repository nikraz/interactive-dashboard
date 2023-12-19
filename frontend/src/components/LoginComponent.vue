<template>
    <div class="login-container">
        <form @submit.prevent="onSubmit" class="login-form">
            <!-- Error Message Display -->
            <div v-if="errorMessage" class="error-message-box">
                {{ errorMessage }}
            </div>
            <div class="form-group" :class="{ 'error': emailError }">
                <label for="email">Email:</label>
                <input id="email" type="email" v-model="email" @input="validateEmail" />
                <span v-if="emailError" class="error-message">{{ emailError }}</span>
            </div>
            <div class="form-group" :class="{ 'error': passwordError }">
                <label for="password">Password:</label>
                <input id="password" type="password" v-model="password" @input="validatePassword" />
                <span v-if="passwordError" class="error-message">{{ passwordError }}</span>
            </div>
            <button type="submit" class="submit-button">Login</button>
        </form>
        <router-link to="/forgot-password" class="forgot-password-link">Forgot Password?</router-link>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex';

export default {
    data() {
        return {
            email: '',
            password: '',
            emailError: '',
            passwordError: '',
            errorMessage: ''
        };
    },
    computed: {
        ...mapState('login', ['isAuthenticated']),
    },
    methods: {
        ...mapActions('login', ['login']),

        validateEmail() {
            const emailRegex = /\S+@\S+\.\S+/;
            this.emailError = emailRegex.test(this.email) ? '' : 'Please enter a valid email address';
        },

        validatePassword() {
            this.passwordError = this.password.length >= 6 ? '' : 'Password must be at least 6 characters';
        },

        validateInput() {
            this.validateEmail();
            this.validatePassword();
            return !this.emailError && !this.passwordError;
        },

        async onSubmit() {
            if (this.validateInput()) {
                try {
                    await this.login({ email: this.email, password: this.password });

                    if (this.isAuthenticated) {
                        this.$router.push('/market-watch');
                        // this.$router.push('/dashboard');
                    } else {
                        this.errorMessage = 'Provided credentials do not match our records';
                    }
                } catch (error) {
                    if (error.response && error.response.status === 401) {
                        this.errorMessage = 'Provided credentials do not match our records';
                    } else {
                        this.errorMessage = 'An error occurred. Please try again later.';
                    }
                    console.error('Login failed:', error);
                }
            }
        },
    }
};
</script>

<style scoped>
.login-container {
    max-width: 300px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

.login-form .form-group {
    margin-bottom: 15px;
}

.login-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

.login-form input[type="email"],
.login-form input[type="password"] {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

.submit-button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 4px;
    background-color: #007bff;
    color: white;
    font-size: 16px;
    cursor: pointer;
}

.submit-button:hover {
    background-color: #0056b3;
}

.error-message-box {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ffa0a0;
    background-color: #ffdada;
    color: #d8000c;
    border-radius: 4px;
    text-align: center;
}

.form-group.error input {
    border-color: #d8000c;
}

.error-message {
    color: #d8000c;
    font-size: 0.8em;
    margin-top: 5px;
}

.forgot-password-link {
    display: block;
    text-align: center;
    margin-top: 15px; /* Spacing above the link */
    padding: 10px;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.forgot-password-link:hover {
    text-decoration: underline;
    background-color: #e7f5ff; /* Light blue background on hover */
}
</style>
