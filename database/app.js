const express = require('express'); 
const app = express();
const mysql = require('mysql'); 
const MySQLDatabase = require('./mySql');
const CarService = require('../services/carService'); 
const CarsController = require('../controllers/carsController');
const PORT = 3000;
// Database configuration
const poolConfig = {
    connectionLimit: 10,
    host: 'drivesmart.mysql.database.azure.com', 
    user: 'admini',
    password: 'DriveSmart4', 
    database: 'drivesmartdb',
    ssl: { 
        // Your SSL options here 
        // For example, you might specify the path to your SSL certificate files 
        ca: 'path_to_ca_certificate', 
        key: 'path_to_client_key', 
        cert: 'path_to_client_certificate' 
    } 
}
// Create database instance
const db = new MySQLDatabase(poolConfig);
// Create service instance
const service = new CarService(db);
// Create controller instance
const controller = new CarsController(service);
// Route setup
app.get('/cars', (req, res) => {
    controller.getAllCars(req, res);
});

app.get('/cars/:id', (req, res) => {
    controller.getCarById(req, res);
});

// Start the serverconst PORT = 3000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});

