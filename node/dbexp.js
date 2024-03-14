const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
var config=require('../node/config');


const app = express();
const port = 3000;

// Create a MySQL connection pool (use your database configuration)
const pool = mysql.createPool(config.connection);

// Middleware to parse JSON requests
app.use(bodyParser.json());

// Define a route to handle SQL queries
app.post('/query', (req, res) => {
  const { query } = req.body;

  // Use the connection pool to execute the query
  pool.getConnection((err, connection) => {
    if (err) {
      console.error('Error getting database connection:', err);
      res.status(500).json({ error: 'Database error' });
      return;
    }

    connection.query(query, (err, results) => {
      connection.release(); // Release the connection back to the pool

      if (err) {
        console.error('Error executing SQL query:', err);
        res.status(500).json({ error: 'Query error' });
        return;
      }

      res.json({ data: results });
    });
  });
});

app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});
