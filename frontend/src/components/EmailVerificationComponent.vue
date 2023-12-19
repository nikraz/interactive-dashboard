<template>
    <div v-if="errorMessage" class="error-message-box">
        {{ errorMessage }}
    </div>
</template>

<script>
import {mapActions, mapState} from 'vuex';

export default {
    data() {
        return {
            errorMessage: ''
        };
    },
    computed: {
        ...mapState('emailVerification', ['emailSent']),
    },
    methods: {
        ...mapActions('emailVerification', ['sendEmailVerification']),
    },
    mounted() {
        try {
            this.sendEmailVerification();

            this.errorMessage = 'Verification Email Sent!'
            setTimeout(() => {
                this.$router.push('/dashboard');
            }, 5000);
        } catch (error) {
            if (error.response && error.response.status === 401) {
                this.errorMessage = 'Provided email does not match our records';
            } else {
                this.errorMessage = 'An error occurred. Please try again later.';
            }
            console.error('Validate email failed:', error);
        }
    }
};
</script>
