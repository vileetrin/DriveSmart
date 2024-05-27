// CarCard.js
import React, { useState } from 'react';
import './CarCard.css'
import CarDetailItem from './CarListItem/CarDetailItem';
import { ReactComponent as HeartIcon } from '../heart.svg';


const CarCard = ({ car }) => {
  const [isFavorite, setIsFavorite] = useState(false);

  const toggleFavorite = () => {
    setIsFavorite(!isFavorite);
  };
  return (
    <div className="CarCard">
    <div className="body__elem">
      <div className="elem__img"> 
        <img src={car.image} alt={car.model} />
      </div>
      <div className="elem__text">
        <h3>{car.model}</h3>
        <div>
        <CarDetailItem name={"Категорія"} value={car?.category} />
        <CarDetailItem name={"Колір"} value={car?.color} />
        </div>
        <div>
        <CarDetailItem name={"Ціна за годину"} value={car?.price_per_hour} />
        <CarDetailItem
          name={"Марка і модель"}
          value={` ${car?.make} ${car?.model}`} />
      </div>
      </div>
      <div className="elem__button"> <button onClick={toggleFavorite} className="heart-button">
          <HeartIcon className={isFavorite ? 'heart-icon favorite' : 'heart-icon'} />
        </button>
        <button type="submit" className="elem__button__elements" name={car.id}>
          {car.price_per_hour > 0 ? "Орендувати" : "Недоступно для оренди"}
        </button>
      </div>
     
    </div>
    </div>
  );
};

export default CarCard;
