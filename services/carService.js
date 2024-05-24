class CarService {
    constructor(db) {
        this.db = db;
    }
    getAllCars(callback) {
        this.db.query('SELECT * FROM cars', [], callback);
    }
    getCarById(id, callback) {
        this.db.query('SELECT * FROM cars WHERE id = ?', [id], callback);
    }
    // Add more database operations here
}
module.exports = CarService;
