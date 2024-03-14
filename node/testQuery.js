const axios = require('axios');

const query = 'SELECT * FROM your_table';
axios.post('http://localhost:3000/query', { query })
  .then(response => {
    console.log('Data from the server:', response.data);
  })
  .catch(error => {
    console.error('Error:', error);
  });
