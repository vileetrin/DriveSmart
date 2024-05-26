import React from "react";
import "./ACtions.css";

export default function Actions({onClick}) {
  return (
    <div className="">
      <h2>Дії</h2>
      <div className="car-actions">
        <button className="filled">редагувати</button>
        <button onClick={onClick} className="outlined">видалити</button>
      </div>
    </div>
  );
}
