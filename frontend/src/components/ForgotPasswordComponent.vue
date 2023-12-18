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
            <button type="submit" class="submit-button">Send Password</button>
        </form>
    </div>
</template>

<script>
import { mapActions, mapState } from 'vuex';

export default {
    data() {
        return {
            email: '',
            emailError: '',
            errorMessage: ''
        };
    },
    computed: {
        ...mapState('password', ['passwordStatus']),
    },
    methods: {
        ...mapActions('password', ['resetPasswordEmail']),

        validateEmail() {
            const emailRegex = /\S+@\S+\.\S+/;
            this.emailError = emailRegex.test(this.email) ? '' : 'Please enter a valid email address';
        },

        validateInput() {
            this.validateEmail();
            return !this.emailError;
        },

        async onSubmit() {
            if (this.validateInput()) {
                try {
                    await this.resetPasswordEmail({ email: this.email });

                    if (this.passwordStatus) {
                        this.errorMessage = this.passwordStatus.message;
                    } else {
                        this.$router.push('/login');
                    }
                } catch (error) {
                    if (error.response && error.response.status === 401) {
                        this.errorMessage = 'Provided email does not match our records';
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
</style>
