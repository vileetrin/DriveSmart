import React from "react";

export default function CarDetailItem({ name, value }) {
  return (
    <div
      style={{
        display: "flex",
        justifyContent: "space-between",
      }}
    >
      <span>{name}</span>
      <span>{value}</span>
    </div>
  );
}
