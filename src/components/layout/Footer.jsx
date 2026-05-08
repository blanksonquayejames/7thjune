import { Link } from 'react-router-dom';
import { BsTruck, BsShieldCheck, BsArrowRepeat, BsHeadset, BsFacebook, BsTwitter, BsInstagram } from 'react-icons/bs';
import './Footer.css';

export default function Footer() {
  return (
    <footer className="footer" id="main-footer">
      <div className="container">
        <div className="footer__grid">
          <div className="footer__col">
            <h3 className="footer__brand">7th June Computers</h3>
            <p className="footer__desc">All the tech you need, all in one place. Discover the latest electronics, computers, and peripherals at unbeatable prices.</p>
            <div className="footer__socials">
              <a href="#" className="footer__social" aria-label="Facebook"><BsFacebook /></a>
              <a href="#" className="footer__social" aria-label="Twitter"><BsTwitter /></a>
              <a href="#" className="footer__social" aria-label="Instagram"><BsInstagram /></a>
            </div>
          </div>
          <div className="footer__col">
            <h4 className="footer__heading">Quick Links</h4>
            <Link to="/" className="footer__link">Home</Link>
            <Link to="/products" className="footer__link">Products</Link>
            <Link to="/cart" className="footer__link">Cart</Link>
            <Link to="/orders" className="footer__link">My Orders</Link>
          </div>
          <div className="footer__col">
            <h4 className="footer__heading">Categories</h4>
            <Link to="/products?category=computers" className="footer__link">Computers</Link>
            <Link to="/products?category=storage-components" className="footer__link">Storage & Components</Link>
            <Link to="/products?category=networking" className="footer__link">Networking</Link>
            <Link to="/products?category=tablets" className="footer__link">Tablets</Link>
            <Link to="/products?category=peripherals" className="footer__link">Peripherals</Link>
          </div>
          <div className="footer__col">
            <h4 className="footer__heading">Contact</h4>
            <p className="footer__text">📍 Accra, Ghana</p>
            <p className="footer__text">📞 +233 20 123 4567</p>
            <p className="footer__text">✉️ info@7thjunecomputers.com</p>
          </div>
        </div>
        <div className="footer__bottom">
          <p>&copy; {new Date().getFullYear()} 7th June Computers. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
}
