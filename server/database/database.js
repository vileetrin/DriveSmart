const mysql = require("mysql2");
class MySQLDatabase {
  constructor(config) {
    this.pool = mysql.createPool(config);
  }
  query(sql, args, callback) {
    this.pool.getConnection((err, connection) => {
      if (err) {
        console.error("Error getting connection from pool: " + err.stack);
        callback(err, null);
        return;
      }
      connection.query(sql, args, (err, results, fields) => {
        connection.release(); // Release the connection back to the pool
        if (err) {
          console.error("Error executing query: " + err.stack);
          callback(err, null);
          return;
        }
        callback(null, results, fields);
      });
    });
  }

  end() {
    this.pool.end((err) => {
      if (err) {
        console.error("Error ending pool: " + err.stack);
        return;
      }
      console.log("Pool ended");
    });
  }
}
module.exports = MySQLDatabase;
