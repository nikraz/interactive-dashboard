const WebSocket = require('ws');

// Connect to the WebSocket server on the same container
const ws = new WebSocket('ws://0.0.0.0:6001');

ws.on('open', function open() {
    console.log('Connected to server');
    // You can send a message to the server if needed
    // ws.send('something');
});

ws.on('message', function incoming(data) {
    console.log('Message from server:', data);
});

ws.on('close', function close() {
    console.log('Disconnected from server');
});

ws.on('error', function error(error) {
    console.error('Error:', error);
});
