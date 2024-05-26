import React from "react";
import "./CarlistItem.css";
import Actions from "./Actions";
import CarDetails from "./CarDetails";

export default function CarListItem({ car, deleteHandler }) {
  return (
    <div className="cars-container">
      <div
        className="item"
        style={{
          padding: "1rem",
        }}
      >
        <img
          alt="some nice car"
          height="auto"
          width={"100%"}
          loading="lazy"
          src={car.image}
        />
      </div>
      <div className="item">
        <CarDetails car={car} />;
      </div>
      <div className="item">
        <Actions onClick={deleteHandler} />
      </div>
    </div>
  );
}
