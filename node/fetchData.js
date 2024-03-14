const axios = require('axios');

const query = 'select * from vehicle';
const serverUrl = 'http://localhost:3000/query';

axios.post(serverUrl, { query })
  .then(response => {
    console.log('Data from the server:', response.data.data);
  })
  .catch(error => {
    console.error('Error:', error);
  });
