const {
  getAllCarsQuery,
  createTableQuery,
  createCarQuery,
  getCarByIdQuery,
  deleteCarQuery,
  carSearchQuery,
} = require("../queries/carQueries");

class CarService {
  constructor(db) {
    this.db = db;
  }
  getAllCars(callback) {
    return this.db.query(getAllCarsQuery, [], callback);
  }
  getCarById(id, callback) {
    return this.db.query(getCarByIdQuery, [id], callback);
  }
  createTable(callback) {
    this.db.query(createTableQuery, [], callback);
  }
  createCar(car, callback) {
    this.db.query(
      createCarQuery,
      [
        car.car_id,
        car.make,
        car.model,
        car.category,
        car.color,
        car.image,
        car.price_per_hour,
      ],
      callback
    );
  }
  deleteCar(id, callback) {
    this.db.query(deleteCarQuery, [id], callback);
  }
  searchCar(data, callback) {
    console.log({data});
    this.db.query(carSearchQuery, data, callback);
  }
  filterCars(color, make, category, callback) {
    this.db.query(filterCarsQuery, [color, make, category], callback)
  }

  // Add more database operations here
}
module.exports = CarService;
