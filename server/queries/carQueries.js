const createTableQuery = `
CREATE TABLE IF NOT EXISTS cars (
  car_id INT AUTO_INCREMENT PRIMARY KEY,
  make VARCHAR(255) NOT NULL,
  model VARCHAR(255) NOT NULL,
  category VARCHAR(255),
  color VARCHAR(255),
  image VARCHAR(255),
  price_per_hour DECIMAL(10, 2)
);

);`;
const createCarQuery = `
  INSERT INTO cars (make, model, category, color, image, price_per_hour)
  VALUES (?, ?, ?, ?, ?, ?);
`;

const getAllCarsQuery = `
SELECT car_id, make, model, image, price_per_hour, category, color
FROM cars;
`;


const getCarByIdQuery = "SELECT * FROM cars WHERE car_id = ?;";

const deleteCarQuery = "DELETE FROM cars WHERE car_id = ?;";

const carSearchQuery = `
SELECT car_id, make, model, image, price_per_hour, category, color
FROM cars
WHERE LOWER(color) LIKE ?
OR LOWER(make) LIKE ?
OR LOWER(model) LIKE ?;
`;



module.exports = {
  createTableQuery,
  createCarQuery,
  getAllCarsQuery,
  getCarByIdQuery,
  deleteCarQuery,
  carSearchQuery,
};
