import React from 'react';
import './mainPage.css';
import Header from '../Header/Header';
import Footer from '../Footer/Footer';
import carImage from '../../img/car.png';

const MainPage = () => {
  return (
    <div className="MainPage">
      <Header />
      <section className="hero-section">
        <div className="hero">
          <div className="hero-wrapper">
            <h1 className="hero-title">
              Каршеринг <span className="part-of-title">DriveSmart</span> - швидко, зручно та дешево!
            </h1>
            <p className="hero-text">Відкрийте новий світ автомобільних подорожей</p>
            <div className="hero-btn-wrapper">
              <a href="patvgj" className="rent-link">Орендувати</a>
              <button type="button" className="download-btn">
                Завантажити додаток
              </button>
            </div>
          </div>
          <img src={carImage} alt="car" className="hero-img" />
        </div>
      </section>
      <section className="benefits-section">
        <div className="benefits">
          <h2 className="benefits-title">
            Чому обирають <span className="part-of-title">DriveSmart</span>:
          </h2>
          <ul className="benefits-list">
            <li className="benefits-list-item">
              <p className="benefits-list-title">Зручність і доступність</p>
              <p className="benefits-list-text">
                Замовте автомобіль за кілька клацань та вирушайте у свою подорож відразу.
              </p>
            </li>
            <li className="benefits-list-item">
              <p className="benefits-list-title">Висока якість обслуговування</p>
              <p className="benefits-list-text">
                Наша команда завжди готова забезпечити вам найкращий досвід користування.
              </p>
            </li>
            <li className="benefits-list-item">
              <p className="benefits-list-title">Прозорі та прості тарифи</p>
              <p className="benefits-list-text">
                Без прихованих витрат – з DriveSmart ви завжди будете знати, скільки коштує ваша подорож.
              </p>
            </li>
            <li className="benefits-list-item">
              <p className="benefits-list-title">Широкий вибір автомобілів</p>
              <p className="benefits-list-text">
                Наш сервіс включає в себе різноманітні моделі для будь-яких потреб та уподобань.
              </p>
            </li>
          </ul>
          <p className="benefits-end-text">
            Плануйте свою наступну подорож з DriveSmart вже зараз!
          </p>
        </div>
      </section>
      <Footer />
    </div>
  );
};

export default MainPage;

