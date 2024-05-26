const mysql = require("mysql2");
const dotenv = require("dotenv");

// Create a connection to the database
const connection = mysql.createConnection({
  connectionLimit: 10,
  host: 'drivesmart.mysql.database.azure.com', 
  user: 'admini',
  password: 'DriveSmart4', 
  database: 'drivesmartdb',
  ssl: { 
    // Your SSL options here 
    // For example, you might specify the path to your SSL certificate files 
    ca: '/certs/DigiCertGlobalRootG2.pem', 
    rejectUnauthorized: false,}
});

// Connect to the database
connection.connect((err) => {
  if (err) {
    console.error("Error connecting to the database:", err);
    return;
  }
  console.log("Connected to the database.");
});

// Sample data to insert
const users = [
  { first_name: "Alice", last_name: "Smith", login: "alice_smith", email: "alice@example.com", password: "password123", role: "user" },
  { first_name: "Bob", last_name: "Johnson", login: "bob_johnson", email: "bob@example.com", password: "password123", role: "user" },
];

const cars = [
  {
    make: "Toyota",
    model: "Corolla",
    category: "Economy",
    color: "Blue",
    image: "https://cdn.jdpower.com/Average%20Weight%20Of%20A%20Car.jpg",
    price_per_hour: 15.5
  },
  {
    make: "Honda",
    model: "Civic",
    category: "Compact",
    color: "Red",
    image: "https://cdn.jdpower.com/Average%20Weight%20Of%20A%20Car.jpg",
    price_per_hour: 14.75
  },
  {
    make: "Ford",
    model: "Focus",
    category: "Sedan",
    color: "Black",
    image: "https://cdn.jdpower.com/Average%20Weight%20Of%20A%20Car.jpg",
    price_per_hour: 20.0
  },
];

// Function to seed users
const seedUsers = () => {
  return new Promise((resolve, reject) => {
    users.forEach((user) => {
      connection.query(
        "INSERT INTO users (first_name, last_name, login, email, password, role) VALUES (?, ?, ?, ?, ?, ?)",
        [user.first_name, user.last_name, user.login, user.email, user.password, user.role],
        (err, results) => {
          if (err) return reject(err);
        }
      );
    });
    resolve();
  });
};

// Function to seed cars
const seedCars = () => {
  return new Promise((resolve, reject) => {
    cars.forEach((car) => {
      connection.query(
        "INSERT INTO cars (make, model, category, color, image, price_per_hour) VALUES (?, ?, ?, ?, ?, ?)",
        [
          car.make,
          car.model,
          car.category,
          car.color,
          car.image,
          car.price_per_hour,
        ],
        (err, results) => {
          if (err) return reject(err);
        }
      );
    });
    resolve();
  });
};

// Seed the database
const seedDatabase = async () => {
  try {
    await seedUsers();
    await seedCars();
    console.log("Database seeded successfully.");
  } catch (error) {
    console.error("Error seeding the database:", error);
  } finally {
    connection.end();
  }
};

seedDatabase();