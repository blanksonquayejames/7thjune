import { useState, useEffect, useRef, useCallback } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { BsSearch, BsCart3, BsPerson, BsList, BsX, BsBoxArrowRight, BsGrid, BsShop, BsMoonStars, BsSun, BsArrowRight, BsBoxSeam, BsPersonCircle } from 'react-icons/bs';
import { useAuth } from '../../context/AuthContext';
import { useCart } from '../../context/CartContext';
import { getProducts } from '../../services/api';
import './Navbar.css';

export default function Navbar() {
  const { user, logout, isAdmin } = useAuth();
  const { itemCount } = useCart();
  const [search, setSearch] = useState('');
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [profileOpen, setProfileOpen] = useState(false);
  const [suggestions, setSuggestions] = useState([]);
  const [showSuggestions, setShowSuggestions] = useState(false);
  const [darkMode, setDarkMode] = useState(() => localStorage.getItem('theme') === 'dark');
  const navigate = useNavigate();
  const profileRef = useRef(null);
  const searchRef = useRef(null);

  // Search submit
  const handleSearch = (e) => {
    e.preventDefault();
    if (search.trim()) {
      navigate(`/products?search=${encodeURIComponent(search.trim())}`);
      setSearch('');
      setSuggestions([]);
      setShowSuggestions(false);
    }
  };

  // Live suggestions — debounced
  const fetchSuggestions = useCallback(async (query) => {
    if (!query.trim()) { setSuggestions([]); return; }
    try {
      const products = await getProducts({ search: query });
      setSuggestions(products.slice(0, 6));
    } catch { setSuggestions([]); }
  }, []);

  useEffect(() => {
    const timer = setTimeout(() => fetchSuggestions(search), 250);
    return () => clearTimeout(timer);
  }, [search, fetchSuggestions]);

  // Show/hide suggestions based on input focus and content
  const handleSearchFocus = () => {
    if (search.trim()) setShowSuggestions(true);
  };

  useEffect(() => {
    if (search.trim()) setShowSuggestions(true);
    else setShowSuggestions(false);
  }, [search]);

  // Close suggestions on outside click
  useEffect(() => {
    const handleClick = (e) => {
      if (searchRef.current && !searchRef.current.contains(e.target)) {
        setShowSuggestions(false);
      }
      if (profileRef.current && !profileRef.current.contains(e.target)) {
        setProfileOpen(false);
      }
    };
    document.addEventListener('mousedown', handleClick);
    return () => document.removeEventListener('mousedown', handleClick);
  }, []);

  // Dark mode toggle
  useEffect(() => {
    document.documentElement.setAttribute('data-theme', darkMode ? 'dark' : 'light');
    localStorage.setItem('theme', darkMode ? 'dark' : 'light');
  }, [darkMode]);

  // Close sidebar on Escape
  useEffect(() => {
    const handleEsc = (e) => {
      if (e.key === 'Escape') {
        setSidebarOpen(false);
        setShowSuggestions(false);
      }
    };
    document.addEventListener('keydown', handleEsc);
    return () => document.removeEventListener('keydown', handleEsc);
  }, []);

  // Lock body scroll when sidebar is open
  useEffect(() => {
    document.body.style.overflow = sidebarOpen ? 'hidden' : '';
    return () => { document.body.style.overflow = ''; };
  }, [sidebarOpen]);

  const closeSidebar = () => setSidebarOpen(false);

  const selectSuggestion = (slug) => {
    navigate(`/products/${slug}`);
    setSearch('');
    setSuggestions([]);
    setShowSuggestions(false);
  };

  return (
    <>
      <nav className="navbar" id="main-navbar">
        <div className="container navbar__inner">
          <Link to="/" className="navbar__brand">
            <img src="/images/logo.png" alt="7th June Computers" className="navbar__logo" />
            <span className="navbar__brand-text">7th June Computers</span>
          </Link>

          {/* Search with live suggestions */}
          <form className="navbar__search" onSubmit={handleSearch} ref={searchRef}>
            <BsSearch className="navbar__search-icon" />
            <input
              type="text"
              placeholder="Search products..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              onFocus={handleSearchFocus}
              className="navbar__search-input"
              id="search-input"
              autoComplete="off"
            />
            {search && (
              <button type="button" className="navbar__search-clear" onClick={() => { setSearch(''); setSuggestions([]); setShowSuggestions(false); }}>
                <BsX />
              </button>
            )}

            {/* Suggestions dropdown */}
            {showSuggestions && (
              <div className="navbar__suggestions">
                {suggestions.length > 0 ? (
                  <>
                    <p className="navbar__suggestions-label">Suggestions</p>
                    {suggestions.map(product => (
                      <button
                        key={product.id}
                        type="button"
                        className="navbar__suggestion"
                        onClick={() => selectSuggestion(product.slug)}
                      >
                        <div className="navbar__suggestion-img" style={{ background: product.bg_color || '#f0f0f0' }}>
                          <img src={product.image} alt={product.name} />
                        </div>
                        <div className="navbar__suggestion-info">
                          <span className="navbar__suggestion-name">{product.name}</span>
                          <span className="navbar__suggestion-price">₵{Number(product.price).toFixed(2)}</span>
                        </div>
                        <BsArrowRight className="navbar__suggestion-arrow" />
                      </button>
                    ))}
                    <button
                      type="submit"
                      className="navbar__suggestions-viewall"
                    >
                      View all results for "{search}"
                    </button>
                  </>
                ) : search.trim() ? (
                  <div className="navbar__suggestions-empty">
                    No products found for "<strong>{search}</strong>"
                  </div>
                ) : null}
              </div>
            )}
          </form>

          <div className="navbar__actions">
            {/* Desktop cart */}
            <Link to="/cart" className="navbar__action-btn navbar__action-btn--desktop" id="cart-btn" title="Cart">
              <BsCart3 />
              {itemCount > 0 && <span className="navbar__badge">{itemCount}</span>}
            </Link>

            {/* Desktop profile */}
            {user ? (
              <div className="navbar__profile navbar__profile--desktop" ref={profileRef}>
                <button className="navbar__action-btn" onClick={() => setProfileOpen(!profileOpen)} id="profile-btn">
                  <BsPerson />
                </button>
                {profileOpen && (
                  <div className="navbar__dropdown">
                    <div className="navbar__dropdown-header">
                      <strong>{user.name}</strong>
                      <span>{user.email}</span>
                    </div>
                    <div className="navbar__dropdown-divider" />
                    <Link to="/orders" className="navbar__dropdown-item" onClick={() => setProfileOpen(false)}>
                      <BsShop /> My Orders
                    </Link>
                    <Link to="/profile" className="navbar__dropdown-item" onClick={() => setProfileOpen(false)}>
                      <BsPerson /> Profile
                    </Link>
                    {isAdmin && (
                      <Link to="/admin" className="navbar__dropdown-item" onClick={() => setProfileOpen(false)}>
                        <BsGrid /> Admin Dashboard
                      </Link>
                    )}
                    <div className="navbar__dropdown-divider" />
                    <button className="navbar__dropdown-item navbar__dropdown-item--danger" onClick={() => { logout(); setProfileOpen(false); }}>
                      <BsBoxArrowRight /> Logout
                    </button>
                  </div>
                )}
              </div>
            ) : (
              <Link to="/login" className="btn btn--primary btn--sm btn--pill navbar__signin--desktop" id="login-btn">Sign In</Link>
            )}

            {/* Mobile: Cart icon */}
            <Link to="/cart" className="navbar__action-btn navbar__mobile-cart" id="mobile-cart-btn" title="Cart">
              <BsCart3 />
              {itemCount > 0 && <span className="navbar__badge">{itemCount}</span>}
            </Link>

            {/* Mobile: Hamburger */}
            <button className="navbar__hamburger" onClick={() => setSidebarOpen(true)} id="mobile-menu-btn" aria-label="Open menu">
              <BsList />
            </button>
          </div>
        </div>
      </nav>

      {/* ═══ Mobile Sidebar ═══ */}
      <div className={`sidebar-overlay${sidebarOpen ? ' sidebar-overlay--active' : ''}`} onClick={closeSidebar} />
      <aside className={`sidebar${sidebarOpen ? ' sidebar--open' : ''}`}>
        <div className="sidebar__header">
          <Link to="/" className="sidebar__brand" onClick={closeSidebar}>
            <img src="/images/logo.png" alt="7th June Computers" className="sidebar__logo" />
            <span className="sidebar__brand-text">7th June Computers</span>
          </Link>
          <button className="sidebar__close" onClick={closeSidebar} aria-label="Close menu">
            <BsX />
          </button>
        </div>

        <div className="sidebar__top-row">
          <Link to="/cart" className="sidebar__cart-pill" onClick={closeSidebar}>
            <BsCart3 />
            <span>Cart</span>
            {itemCount > 0 && <span className="sidebar__cart-count">{itemCount}</span>}
          </Link>
          <button className="sidebar__theme-toggle" onClick={() => setDarkMode(!darkMode)} title={darkMode ? 'Light mode' : 'Dark mode'}>
            {darkMode ? <BsSun /> : <BsMoonStars />}
            <span>{darkMode ? 'Light' : 'Dark'}</span>
          </button>
        </div>

        {user ? (
          <div className="sidebar__user-section">
            <div className="sidebar__user-info">
              <div className="sidebar__avatar">
                <BsPersonCircle />
              </div>
              <div>
                <strong className="sidebar__user-name">{user.name}</strong>
                <span className="sidebar__user-email">{user.email}</span>
              </div>
            </div>
            <div className="sidebar__divider" />
            <Link to="/profile" className="sidebar__link" onClick={closeSidebar}>
              <BsPerson className="sidebar__link-icon" /> Profile
            </Link>
            <Link to="/orders" className="sidebar__link" onClick={closeSidebar}>
              <BsBoxSeam className="sidebar__link-icon" /> My Orders
            </Link>
            {isAdmin && (
              <Link to="/admin" className="sidebar__link" onClick={closeSidebar}>
                <BsGrid className="sidebar__link-icon" /> Admin Dashboard
              </Link>
            )}
            <div className="sidebar__divider" />
            <button className="sidebar__link sidebar__link--danger" onClick={() => { logout(); closeSidebar(); }}>
              <BsBoxArrowRight className="sidebar__link-icon" /> Logout
            </button>
          </div>
        ) : (
          <div className="sidebar__auth-section">
            <Link to="/login" className="btn btn--primary btn--pill sidebar__auth-btn" onClick={closeSidebar}>
              Sign In
            </Link>
            <Link to="/register" className="btn btn--outline btn--pill sidebar__auth-btn" onClick={closeSidebar}>
              Create Account
            </Link>
          </div>
        )}

        <div className="sidebar__nav">
          <div className="sidebar__divider" />
          <Link to="/" className="sidebar__link" onClick={closeSidebar}>Home</Link>
          <Link to="/products" className="sidebar__link" onClick={closeSidebar}>Products</Link>
        </div>
      </aside>
    </>
  );
}
