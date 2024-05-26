import React, { useEffect, useState } from "react";
import CarListItem from "./CarListItem/CarListItem";
import { deleteCar, getCars, searchCar } from "../../api/cars";
import CarsHeader from "../SearchPanel/SearchPanel";

export default function Cars() {
  const [cars, setCars] = useState([]);

  useEffect(() => {
    getCars().then((res) => {
      setCars(res);
    });
  }, []);
  const deleteHandler = (id) => {
    deleteCar(id).then(() => {
      getCars().then((res) => {
        setCars(res);
      });
    });
  };
  const searchHandler = (value) => {
    searchCar(value).then((res) => {
      console.log({ res });
      setCars(res);
    });
  };
  return (
    <div>
      <CarsHeader
        title={
          <span>
            Управління <span style={{ color: "orange" }}>автомобілями</span>
          </span>
        }
        searchHandler={searchHandler}
      />
      {cars?.length &&
        cars?.map((car) => {
          return (
            <CarListItem
              car={car}
              deleteHandler={() => deleteHandler(car.id)}
            />
          );
        })}
    </div>
  );
}
