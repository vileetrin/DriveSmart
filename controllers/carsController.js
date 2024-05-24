class CarController {
    constructor(service) {
        this.service = service;
    }
    getAllCars(req, res) {
        this.service.getAllCars((err, results) => {
            if (err) {
                res.status(500).json({ error: 'Internal Server Error' }); return;
            } res.json(results);
        });
    }

    getCarById(req, res) {
        this.service.getCarById((err, results) => {
            if (err) {
                res.status(500).json({ error: 'Internal Server Error' }); return;
            } res.json(results);
        });
    }
}
// Add more controller methods here}
module.exports = CarController;
