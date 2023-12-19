const WebSocket = require('ws');
const axios = require('axios');
const url = require('url');

// Create a WebSocket server on port 6001
const wss = new WebSocket.Server({
    port: 6001,
    verifyClient: (info, done) => {
        // Parse the query string to extract the token
        const parsedUrl = url.parse(info.req.url, true);
        info.req.token = parsedUrl.query.token;
        done(true);
    }
});

console.log('WebSocket server listening on port 6001');

// Function to generate random currency data
function generateRandomCurrencyData() {
    const currencies = ["EURUSD", "AUDUSD", "EURGBP", "GBPUSD"];
    const prices = currencies.map(symbol => {
        const bid = parseFloat((Math.random() * (1.2 - 1.0) + 1.0).toFixed(5));
        const ask = bid + 0.0003;
        return { symbol, bid: bid.toString(), ask: ask.toString() };
    });

    return {
        last_update: new Date().toLocaleTimeString(),
        prices
    };
}

// Emit currency data at random intervals between 2 to 3 seconds
function emitCurrencyData(ws) {
    const data = generateRandomCurrencyData();
    if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify(data)); // Emitting to the connected client
    }

    setTimeout(() => emitCurrencyData(ws), Math.random() * 1000 + 2000);
}

wss.on('connection', function connection(ws, req) {
    console.log('A user connected');

    // Get the token from the request object
    const token = req.token;

    console.log(token);

    // Verify the token with the Laravel application
    axios.post('http://app/api/verify-token', {}, {
        headers: {
            'Authorization': `Bearer ${token}`,
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (response.data.message === 'token is valid') {
                console.log('Token is valid, connection authorized.');
                // Token is valid, start emitting data
                emitCurrencyData(ws);
            } else {
                // Token is invalid, close the WebSocket connection
                console.log('Token is invalid, connection denied.');
                ws.close();
            }
        })
        .catch(error => {
            console.error('Error during token verification:', error.message);
            ws.close();
        });

    ws.on('disconnect', () => {
        console.log('User disconnected');
    });
});
