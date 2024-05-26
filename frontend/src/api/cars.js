export const getCars = async () => {
  return fetch(`http://localhost:4000/cars`).then((res) => {
    return res.json();
  });
};

export const deleteCar = async (id) => {
  return fetch(`http://localhost:4000/cars/${id}`, { method: "DELETE" });
};
export const searchCar = async (query) => {
  return fetch(`http://localhost:4000/cars/search?search=${query}`, {
    method: "GET",
  }).then((res) => {
    return res.json();
  });
};
