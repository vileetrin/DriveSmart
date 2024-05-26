import React from 'react';
import './Footer.css';

const Footer = () => {
  return (
    <section className="footer-section">
      <footer className="footer">
        <div className="footer-contacts-wrapper">
          <div className="footer-support">
            <p className="footer-contacts-title">Підтримка</p>
            <ul className="support-list">
              <li className="support-list-item">
                <p>+38(066) 111 2 333</p>
              </li>
              <li className="support-list-item">
                <p>drivesmartcustomerservice@gmail.com</p>
              </li>
            </ul>
          </div>
          <div className="ad-wrapper">
            <p className="footer-contacts-title">Рекламні та інші пропозиції</p>
            <p className="ad-email">drivesmartreklama@gmail.com</p>
          </div>
        </div>
        <div className="footer-socials-container">
          <p className="footer-title-socials">Ми в соціальних мережах</p>
          <ul className="footer-social-links-list">
            <li className="footer-social-links-items">
              <a className="footer-social-links" href="">
                <svg className="footer-socials-icon" width="24" height="24">
                  <use href="../img/symbol-defs.svg#icon-instagram"></use>
                </svg>
              </a>
            </li>
            <li className="footer-social-links-items">
              <a className="footer-social-links" href="">
                <svg className="footer-socials-icon" width="24" height="24">
                  <use href="../img/symbol-defs.svg#icon-twitter"></use>
                </svg>
              </a>
            </li>
            <li className="footer-social-links-items">
              <a className="footer-social-links" href="">
                <svg className="footer-socials-icon" width="24" height="24">
                  <use href="../img/symbol-defs.svg#icon-facebook"></use>
                </svg>
              </a>
            </li>
            <li className="footer-social-links-items">
              <a className="footer-social-links" href="">
                <svg className="footer-socials-icon" width="24" height="24">
                  <use href="../img/symbol-defs.svg#icon-linkedin"></use>
                </svg>
              </a>
            </li>
          </ul>
        </div>
        <a href="../index.html" className="footer-logo">DriveSmart</a>
      </footer>
    </section>
  );
};

export default Footer;
