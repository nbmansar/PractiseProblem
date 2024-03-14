var mysql = require('mysql');
var http = require('http');
var config=require('../node/config');

// check server connection 
http.createServer(function (req,res){ 
    res.write('Nodejs started using xampp');
 res.end();     
}).listen(8080);
console.log('http server started');

//make Connnection 
var make_connection = mysql.createConnection(config);

//Check DB connection
    make_connection.connect(function(err) {
        console.log("Connected to XAMPP Server!");
      //sql query to create a database named  facility in XAMPP

        var query_details = `CREATE TABLE vehicle (
            vehicleId INT NOT NULL PRIMARY KEY,
            make VARCHAR(64),
            model VARCHAR(128),
            derivative VARCHAR(255)
        )`;
        make_connection.query(query_details, function (err, result) {
      //Display message in our console.
          console.log("Database-facility is created");
        });
      }); 