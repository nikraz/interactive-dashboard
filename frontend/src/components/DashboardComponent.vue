<template>
    <div style="width: 20em;float: right; margin-bottom: 10em;">
        <router-link to="/logout" class="logout-link">Logout</router-link>
    </div>
    <div style="width: 20em;float: left; margin-bottom: 10em;">
        <router-link to="/email-verification" class="logout-link">Send Email Verification Link</router-link>
    </div>
    <div class="dashboard">

        <table class="market-table">
            <thead>
            <tr>
                <th colspan="3" style="text-align: center;">
                    <h2>Market Watch: {{ this.dashboardDataLastUpdate }}</h2>
                </th>
            </tr>
            <tr>
                <th>Symbol</th>
                <th>Bid</th>
                <th>Ask</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in this.dashboardDataPrices" :key="item.symbol">
                <td>{{ item.symbol }}</td>
                <td :class="getPriceClass(item)">
                    <span class="arrow" v-if="getPriceChange(item) !== null">
                        <img :src="getPriceChange(item) ? '/up-arrow.png' : '/down-arrow.png'" />
                    </span>
                    {{ item.bid }}
                </td>
                <td>{{ item.ask }}</td>
            </tr>
            </tbody>
        </table>
    </div>

</template>

<script>
import { mapActions, mapGetters, mapState } from 'vuex';

export default {
    data() {
        return {
            previousData: {},
            dashboardData: {}
        };
    },
    computed: {
        ...mapGetters('dashboard', ['dashboardDataPrices']),
        ...mapState('dashboard', ['dashboardDataPrices', 'dashboardDataLastUpdate']),
    },
    methods: {
        ...mapActions('dashboard', ['getDashboardData']),
        ...mapActions('logout', ['logout']),
        async fetchData() {
            this.previousData = this.dashboardData;
            await this.getDashboardData();
            if (this.dashboardDataPrices.last_update) {
                this.dashboardData = this.dashboardDataPrices;
            }
        },
        getPriceClass(item) {
            // Determine if the price went up or down by more than 0.0003
            const prevPrice = this.previousData[item.symbol];
            if (prevPrice) {
                const priceChange = parseFloat(item.bid) - parseFloat(prevPrice);
                if (Math.abs(priceChange) > 0.0003) {
                    return priceChange > 0 ? 'price-up' : 'price-down';
                }
            }
            return '';
        },
        getPriceChange(item) {
            // Determine the direction of the price change
            const prevPrice = this.previousData[item.symbol];
            if (prevPrice) {
                const priceChange = parseFloat(item.bid) - parseFloat(prevPrice);
                if (Math.abs(priceChange) > 0.0003) {
                    return priceChange > 0;
                }
            }
            return null;
        }
    },
    watch: {
        dashboardData: {
            deep: true,
            handler(newData) {
                // Update the previousData object with the new prices for comparison in the next update cycle
                this.previousData = {};
                newData.prices.forEach(price => {
                    this.previousData[price.symbol] = price.bid;
                });
            }
        }
    },
    mounted() {
        this.fetchData();
        // Set an interval to fetch updated data every 10 seconds
        setInterval(this.fetchData, 10000);
    }
};
</script>

<style scoped>
.market-table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 1rem;
    font-size: 0.9rem;
}

.market-table thead tr {
    background-color: #f3f3f3;
}

.market-table th,
.market-table td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.price-up {
    color: #4CAF50; /* Green */
}

.price-down {
    color: #F44336; /* Red */
}

.arrow img {
    width: 16px; /* Set the size of your arrow images */
    height: 16px;
    vertical-align: middle; /* Align the arrow with the text */
}

.logout-link {
    display: block;
    text-align: center;
    margin-top: 15px; /* Spacing above the link */
    padding: 10px;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.logout-link:hover {
    text-decoration: underline;
    background-color: #e7f5ff; /* Light blue background on hover */
}
</style>
