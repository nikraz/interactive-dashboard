<template>
    <div style="width: 20em;float: right; margin-bottom: 10em;">
        <router-link to="/logout" class="logout-link">Logout</router-link>
    </div>
    <div style="width: 20em;float: left; margin-bottom: 10em;">
        <router-link to="/email-verification" class="logout-link">Send Email Verification Link</router-link>
    </div>
    <div style="width: 100%; display: inline-grid;">
        <table>
            <thead>
                <tr>
                   <th  style="text-align: center; background: rgb(157 214 223)" colspan="3">
                       Market Watch: {{ lastUpdate }}
                   </th>
                </tr>
            </thead>
            <tr>
                <th>Symbol</th>
                <th>Bid</th>
                <th>Ask</th>
            </tr>
            <tr v-for="price in prices" :key="price.symbol">
                <td>{{ price.symbol }}</td>
                <td :class="{ 'price-up': price.bidChange > 0, 'price-down': price.bidChange < 0 }">
                    {{ price.bid }}
                </td>
                <td :class="{ 'price-up': price.askChange > 0, 'price-down': price.askChange < 0 }">
                    {{ price.ask }}
                </td>
            </tr>
        </table>
    </div>
</template>
<script>
import { mapState } from 'vuex';

export default {
    data() {
        return {
            ws: null,
            prices: [],
            prevPrices: {},
            lastUpdate: '',
        };
    },
    created() {
        this.connectToWebSocket();
    },
    computed: {
        ...mapState('login', ['token']),
    },
    methods: {
        connectToWebSocket() {

            this.ws = new WebSocket(`ws://localhost:6001/?token=` + this.token);

            this.ws.onopen = () => {
                console.log("Connected to WebSocket");
            };

            this.ws.onmessage = (event) => {
                const newData = JSON.parse(event.data);
                this.lastUpdate = newData.last_update;
                newData.prices.forEach((price) => {
                    const symbol = price.symbol;
                    if (this.prevPrices[symbol]) {
                        price.bidChange = price.bid - this.prevPrices[symbol].bid;
                        price.askChange = price.ask - this.prevPrices[symbol].ask;
                    }
                });
                this.prevPrices = newData.prices.reduce((acc, price) => {
                    acc[price.symbol] = { ...price };
                    return acc;
                }, {});
                this.prices = newData.prices;
            };

            this.ws.onclose = () => {
                console.log("Disconnected from WebSocket");
                // Optional: Reconnect logic
            };

            this.ws.onerror = (error) => {
                console.error("WebSocket Error:", error);
            };
        },
    },
};
</script>
<style>
.price-up {
    color: green;
}

.price-down {
    color: red;
}

th {border: 1px solid black;}
td {border: 1px solid black;}

.logout-link {
    display: block;
    text-align: center;
    margin-top: 15px;
    padding: 10px;
    border-radius: 4px;
    color: #007bff;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.logout-link:hover {
    text-decoration: underline;
    background-color: #e7f5ff;
}


</style>
