import React, { useState } from "react";
import "./SearchPanel.css";

const SearchPanel = ({ title, searchHandler }) => {
  const [searchTerm, setSearchTerm] = useState("");
  const [filter, setFilter] = useState("");

  const handleSearchChange = (e) => {
    setSearchTerm(e.target.value);
  };

  const handleFilterChange = (e) => {
    setFilter(e.target.value);
  };

  const handleActionClick = () => {
    alert(
      `Action clicked with search term: ${searchTerm} and filter: ${filter}`
    );
  };

  return (
    <div className="container">
      <h1>{title}</h1>
      <div className="input-container">
        <input
          type="text"
          placeholder="Search..."
          value={searchTerm}
          onChange={handleSearchChange}
          className="input"
        />
        <button
          style={{
            position: "absolute",
            right: 0,
          }}
          disabled={searchTerm === ""}
          onClick={() => {
            searchHandler(searchTerm);
            setSearchTerm("");
          }}
          className="search-button"
        >
          <span>&#9906;</span>
        </button>
      </div>
      <select value={filter} onChange={handleFilterChange} className="select">
        <option value="">No Filter</option>
        <option value="filter1">Filter 1</option>
        <option value="filter2">Filter 2</option>
      </select>
      <button onClick={handleActionClick} className="button">
        <span>&#43;</span>
      </button>
    </div>
  );
};

export default SearchPanel;
