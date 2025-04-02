const https = require('https');
const fs = require('fs');
const express = require('express');

const app = express();

const options = {
  key: fs.readFileSync('localhost-key.pem'),
  cert: fs.readFileSync('localhost.pem'),
};

https.createServer(options, app).listen(8081, () => {
  console.log('Server is running on https://localhost:8081');
});

app.get('/', (req, res) => {
  res.send('Hello, HTTPS World on port 8081!');
});