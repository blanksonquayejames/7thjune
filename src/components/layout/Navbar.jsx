import { useState, useEffect, useRef, useCallback } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { BsSearch, BsCart3, BsPerson, BsList, BsX, BsBoxArrowRight, BsGrid, BsShop, BsMoonStars, BsSun, BsArrowRight, BsBoxSeam, BsPersonCircle } from 'react-icons/bs';
import { useAuth } from '../../context/AuthContext';
import { useCart } from '../../context/CartContext';
import { useConfirm } from '../../context/ConfirmationContext';
import { getProducts } from '../../services/api';
import { formatPrice, getDiscountedPrice } from '../../utils/helpers';
import './Navbar.css';

export default function Navbar() {
  const { user, logout, isAdmin } = useAuth();
  const { itemCount, items, total, removeItem } = useCart();
  const confirm = useConfirm();
  const [search, setSearch] = useState('');
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [profileOpen, setProfileOpen] = useState(false);
  const [suggestions, setSuggestions] = useState([]);
  const [showSuggestions, setShowSuggestions] = useState(false);
  const [darkMode, setDarkMode] = useState(false);
  const [cartAnimated, setCartAnimated] = useState(false);
  const [miniCartOpen, setMiniCartOpen] = useState(false);
  const prevItemCount = useRef(itemCount);
  
  const router = useRouter();
  const profileRef = useRef(null);
  const searchRef = useRef(null);
  const hamburgerRef = useRef(null);
  const closeBtnRef = useRef(null);
  const sidebarRef = useRef(null);

  // Search submit
  const handleSearch = (e) => {
    e.preventDefault();
    if (search.trim()) {
      router.push(`/products?search=${encodeURIComponent(search.trim())}`);
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

  const handleLogout = async () => {
    const hasConfirmed = await confirm({
      title: 'Sign Out',
      message: 'Are you sure you want to log out of your account?',
      confirmText: 'Sign Out',
      cancelText: 'Cancel',
      type: 'logout'
    });
    if (hasConfirmed) {
      logout();
      setProfileOpen(false);
      setSidebarOpen(false);
      router.push('/');
    }
  };

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

  // Read theme only on client-side mount
  useEffect(() => {
    const saved = localStorage.getItem('theme');
    const isDark = saved === 'dark';
    setDarkMode(isDark);
    document.documentElement.setAttribute('data-theme', isDark ? 'dark' : 'light');
  }, []);

  const toggleDarkMode = () => {
    const nextMode = !darkMode;
    setDarkMode(nextMode);
    document.documentElement.setAttribute('data-theme', nextMode ? 'dark' : 'light');
    localStorage.setItem('theme', nextMode ? 'dark' : 'light');
  };

  // Optimistic Cart animation & auto-show mini cart
  useEffect(() => {
    if (itemCount > prevItemCount.current) {
      setCartAnimated(true);
      setMiniCartOpen(true);
      const animTimer = setTimeout(() => setCartAnimated(false), 500);
      const closeTimer = setTimeout(() => setMiniCartOpen(false), 3000);
      return () => {
        clearTimeout(animTimer);
        clearTimeout(closeTimer);
      };
    }
    prevItemCount.current = itemCount;
  }, [itemCount]);

  // Accessibility focus shift when sidebar opens/closes
  useEffect(() => {
    if (sidebarOpen) {
      const timer = setTimeout(() => closeBtnRef.current?.focus(), 50);
      return () => clearTimeout(timer);
    } else {
      hamburgerRef.current?.focus();
    }
  }, [sidebarOpen]);

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

  // Trap keyboard focus within sidebar when open
  const handleSidebarKeyDown = (e) => {
    if (e.key === 'Tab' && sidebarOpen && sidebarRef.current) {
      const focusable = sidebarRef.current.querySelectorAll(
        'a[href], button:not([disabled]), input, select, textarea, [tabindex="0"]'
      );
      if (focusable.length === 0) return;
      const first = focusable[0];
      const last = focusable[focusable.length - 1];

      if (e.shiftKey) {
        if (document.activeElement === first) {
          last.focus();
          e.preventDefault();
        }
      } else {
        if (document.activeElement === last) {
          first.focus();
          e.preventDefault();
        }
      }
    }
  };

  // Lock body scroll when sidebar is open
  useEffect(() => {
    document.body.style.overflow = sidebarOpen ? 'hidden' : '';
    return () => { document.body.style.overflow = ''; };
  }, [sidebarOpen]);

  const closeSidebar = () => setSidebarOpen(false);

  const selectSuggestion = (slug) => {
    router.push(`/products/${slug}`);
    setSearch('');
    setSuggestions([]);
    setShowSuggestions(false);
  };

  return (
    <>
      <nav className="navbar" id="main-navbar">
        <div className="container navbar__inner">
          <Link href="/" className="navbar__brand" aria-label="7th June Computers Home">
            <img src="/images/logo.png" alt="7th June Computers" className="navbar__logo" />
            <span className="navbar__brand-text">7th June Computers</span>
          </Link>

          {/* Desktop Navigation Links */}
          <div className="navbar__links navbar__links--desktop" role="navigation" aria-label="Desktop Navigation">
            <Link href="/" className="navbar__link-item">Home</Link>
            <Link href="/products" className="navbar__link-item">Products</Link>
          </div>

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
            <div 
              className="navbar__cart-container"
              onMouseEnter={() => setMiniCartOpen(true)}
              onMouseLeave={() => setMiniCartOpen(false)}
            >
              <Link 
                href="/cart" 
                className={`navbar__action-btn navbar__action-btn--desktop ${cartAnimated ? 'cart-bounce' : ''}`} 
                id="cart-btn" 
                title="View Cart"
                aria-label={`Shopping cart with ${itemCount} items`}
              >
                <BsCart3 />
                {itemCount > 0 && <span className="navbar__badge">{itemCount}</span>}
              </Link>
              
              {miniCartOpen && (
                <div className="navbar__mini-cart-dropdown" id="mini-cart-dropdown">
                  {items.length === 0 ? (
                    <div className="mini-cart__empty">
                      <span className="mini-cart__empty-icon">📦</span>
                      <p>Your cart is empty</p>
                    </div>
                  ) : (
                    <>
                      <div className="mini-cart__items">
                        {items.map(({ product, quantity }) => (
                          <div key={product.id} className="mini-cart__item">
                            <div className="mini-cart__item-img">
                              {product.image ? (
                                <img src={product.image} alt={product.name} />
                              ) : (
                                <span>📦</span>
                              )}
                            </div>
                            <div className="mini-cart__item-info">
                              <Link 
                                href={`/products/${product.slug}`} 
                                className="mini-cart__item-name"
                                onClick={() => setMiniCartOpen(false)}
                              >
                                {product.name}
                              </Link>
                              <span className="mini-cart__item-meta">
                                {quantity} × {formatPrice(getDiscountedPrice(product))}
                              </span>
                            </div>
                            <button 
                              className="mini-cart__item-remove" 
                              onClick={async (e) => {
                                e.preventDefault();
                                e.stopPropagation();
                                const hasConfirmed = await confirm({
                                  title: 'Remove Item',
                                  message: `Are you sure you want to remove "${product.name}" from your cart?`,
                                  confirmText: 'Remove',
                                  cancelText: 'Cancel',
                                  type: 'danger'
                                });
                                if (hasConfirmed) {
                                  removeItem(product.id);
                                }
                              }}
                              title="Remove item"
                            >
                              <BsX />
                            </button>
                          </div>
                        ))}
                      </div>
                      <div className="mini-cart__footer">
                        <div className="mini-cart__total">
                          <span>Total</span>
                          <strong>{formatPrice(total)}</strong>
                        </div>
                        <div className="mini-cart__actions">
                          <Link 
                            href="/cart" 
                            className="btn btn--outline btn--sm" 
                            style={{ flex: 1, textAlign: 'center', justifyContent: 'center' }}
                            onClick={() => setMiniCartOpen(false)}
                          >
                            View Cart
                          </Link>
                          <Link 
                            href="/checkout" 
                            className="btn btn--accent btn--sm" 
                            style={{ flex: 1, textAlign: 'center', justifyContent: 'center' }}
                            onClick={() => setMiniCartOpen(false)}
                          >
                            Checkout
                          </Link>
                        </div>
                      </div>
                    </>
                  )}
                </div>
              )}
            </div>

            {/* Desktop profile */}
            {user ? (
              <div className="navbar__profile navbar__profile--desktop" ref={profileRef}>
                <button 
                  className="navbar__action-btn" 
                  onClick={() => setProfileOpen(!profileOpen)} 
                  id="profile-btn"
                  aria-label="User profile menu"
                  aria-expanded={profileOpen}
                >
                  <BsPerson />
                </button>
                {profileOpen && (
                  <div className="navbar__dropdown" role="menu">
                    <div className="navbar__dropdown-header">
                      <strong>{user.name}</strong>
                      <span>{user.email}</span>
                    </div>
                    <div className="navbar__dropdown-divider" />
                    <Link href="/orders" className="navbar__dropdown-item" onClick={() => setProfileOpen(false)} role="menuitem">
                      <BsShop /> My Orders
                    </Link>
                    <Link href="/profile" className="navbar__dropdown-item" onClick={() => setProfileOpen(false)} role="menuitem">
                      <BsPerson /> Profile
                    </Link>
                    {isAdmin && (
                      <Link href="/admin" className="navbar__dropdown-item" onClick={() => setProfileOpen(false)} role="menuitem">
                        <BsGrid /> Admin Dashboard
                      </Link>
                    )}
                    <div className="navbar__dropdown-divider" />
                    <button className="navbar__dropdown-item navbar__dropdown-item--danger" onClick={handleLogout} role="menuitem">
                      <BsBoxArrowRight /> Logout
                    </button>
                  </div>
                )}
              </div>
            ) : (
              <Link href="/login" className="btn btn--primary btn--sm btn--pill navbar__signin--desktop" id="login-btn">Sign In</Link>
            )}

            {/* Mobile: Cart icon */}
            <Link 
              href="/cart" 
              className={`navbar__action-btn navbar__mobile-cart ${cartAnimated ? 'cart-bounce' : ''}`} 
              id="mobile-cart-btn" 
              title="View Cart"
              aria-label={`Shopping cart with ${itemCount} items`}
            >
              <BsCart3 />
              {itemCount > 0 && <span className="navbar__badge">{itemCount}</span>}
            </Link>

            {/* Mobile: Hamburger */}
            <button 
              ref={hamburgerRef}
              className="navbar__hamburger" 
              onClick={() => setSidebarOpen(true)} 
              id="mobile-menu-btn" 
              aria-label="Open navigation menu" 
              aria-expanded={sidebarOpen}
            >
              <BsList />
            </button>
          </div>
        </div>
      </nav>

      {/* ═══ Mobile Sidebar ═══ */}
      <div 
        className={`sidebar-overlay${sidebarOpen ? ' sidebar-overlay--active' : ''}`} 
        onClick={closeSidebar} 
        aria-hidden="true"
      />
      <aside 
        ref={sidebarRef}
        className={`sidebar${sidebarOpen ? ' sidebar--open' : ''}`}
        aria-hidden={!sidebarOpen}
        role="dialog"
        aria-modal="true"
        aria-label="Navigation drawer"
        onKeyDown={handleSidebarKeyDown}
      >
        <div className="sidebar__header">
          <Link href="/" className="sidebar__brand" onClick={closeSidebar}>
            <img src="/images/logo.png" alt="7th June Computers" className="sidebar__logo" />
            <span className="sidebar__brand-text">7th June Computers</span>
          </Link>
          <button 
            ref={closeBtnRef}
            className="sidebar__close" 
            onClick={closeSidebar} 
            aria-label="Close navigation menu"
          >
            <BsX />
          </button>
        </div>

        <div className="sidebar__top-row">
          <Link href="/cart" className="sidebar__cart-pill" onClick={closeSidebar} aria-label={`View cart with ${itemCount} items`}>
            <BsCart3 />
            <span>Cart</span>
            {itemCount > 0 && <span className="sidebar__cart-count">{itemCount}</span>}
          </Link>
          <button 
            className="sidebar__theme-toggle" 
            onClick={toggleDarkMode} 
            title={darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'}
            aria-label={darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'}
          >
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
            <Link href="/profile" className="sidebar__link" onClick={closeSidebar}>
              <BsPerson className="sidebar__link-icon" /> Profile
            </Link>
            <Link href="/orders" className="sidebar__link" onClick={closeSidebar}>
              <BsBoxSeam className="sidebar__link-icon" /> My Orders
            </Link>
            {isAdmin && (
              <Link href="/admin" className="sidebar__link" onClick={closeSidebar}>
                <BsGrid className="sidebar__link-icon" /> Admin Dashboard
              </Link>
            )}
            <div className="sidebar__divider" />
            <button className="sidebar__link sidebar__link--danger" onClick={handleLogout}>
              <BsBoxArrowRight className="sidebar__link-icon" /> Logout
            </button>
          </div>
        ) : (
          <div className="sidebar__auth-section">
            <Link href="/login" className="btn btn--primary btn--pill sidebar__auth-btn" onClick={closeSidebar}>
              Sign In
            </Link>
            <Link href="/register" className="btn btn--outline btn--pill sidebar__auth-btn" onClick={closeSidebar}>
              Create Account
            </Link>
          </div>
        )}

        <div className="sidebar__nav">
          <div className="sidebar__divider" />
          <Link href="/" className="sidebar__link" onClick={closeSidebar}>Home</Link>
          <Link href="/products" className="sidebar__link" onClick={closeSidebar}>Products</Link>
        </div>
      </aside>
    </>
  );
}
