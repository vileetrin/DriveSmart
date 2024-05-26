const express = require("express");
const app = express();
const MySQLDatabase = require("./database/database");
const CarService = require("../services/carService");
const CarController = require("./controllers/carController");
const exphbs = require("express-handlebars");
const path = require("path");
const dotenv = require("dotenv");
const cors = require("cors");

dotenv.config();

// Enable all CORS requests
app.use(cors());
// Database configuration
const PORT = 4000;

const poolConfig = {
  connectionLimit: 10,
  host: 'drivesmart.mysql.database.azure.com', 
  user: 'admini',
  password: 'DriveSmart4', 
  database: 'drivesmartdb',
  ssl: { 
    // Your SSL options here 
    // For example, you might specify the path to your SSL certificate files 
    ca: '/certs/DigiCertGlobalRootCa.pem', 
} 
};
app.use(express.json());
// Create database instance
const db = new MySQLDatabase(poolConfig);

// Create service instance
const service = new CarService(db);

// Create controller instance
const controller = new CarController(service);

// Set up Handlebars as the view engine
app.engine(
  "handlebars",
  exphbs.engine({
    defaultLayout: "main",
    layoutsDir: path.join(__dirname, "views", "layouts"),
  })
);
app.set("view engine", "handlebars");
app.set("views", path.join(__dirname, "views"));

app.get("/cars", (req, res) => {
  controller.getCars(req, res);
});
app.get("/cars/search", (req, res) => {
  controller.searchCar(req, res);
});

app.post("/create-table", (req, res) => {
  controller.createTable(req, res);
});

app.post("/cars/create", (req, res) => {
  controller.createCar(req, res);
});

app.get("/cars/:id", (req, res) => {
  controller.getCarById(req, res);
});

app.delete("/cars/:id", (req, res) => {
  controller.deleteCar(req, res);
});
// Start the serverconst PORT = 3000;
app.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
