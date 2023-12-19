<template>
    <div v-if="errorMessage" class="error-message-box">
        {{ errorMessage }}
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';

export default {
    data() {
        return {
            errorMessage: ''
        };
    },
    computed: {
        ...mapGetters('logout', ['getLogoutState']),
    },
    methods: {
        ...mapActions('logout', ['logout']),
    },
    mounted() {
        this.logout();
        if (this.getLogoutState) {
            this.$router.push('/login');
        } else {
            this.errorMessage = 'Provided credentials do not match our records';
            setTimeout(() => {
                this.$router.push('/dashboard');
            }, 5000);
        }
    }
};
</script>
