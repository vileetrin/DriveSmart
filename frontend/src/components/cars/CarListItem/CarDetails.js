import React from "react";
import "./CarDetails.css";
import CarDetailItem from "./CarDetailItem";

export default function CarDetails({ car }) {
  return (
    <div className="car-details-container">
      <h2>Деталі автомобіля</h2>
      <div className="car-details">
        <CarDetailItem name={"Категорія"} value={car?.category} />
        <CarDetailItem name={"Колір"} value={car?.color} />
        <CarDetailItem name={"Ціна за годину"} value={car?.price_per_hour} />
        <CarDetailItem name={"тип"} value={car?.type} />
        <CarDetailItem
          name={"Марка і модель"}
          value={` ${car?.make} ${car?.model}`}
        />
      </div>
    </div>
  );
}
