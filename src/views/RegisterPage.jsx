import { useState, useEffect } from 'react';
import Link from 'next/link';
import { useRouter, useSearchParams } from 'next/navigation';
import { useAuth } from '../context/AuthContext';
import './AuthPages.css';

export default function RegisterPage() {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [confirm, setConfirm] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const { register } = useAuth();
  const router = useRouter();
  const searchParams = useSearchParams();
  const redirectTo = searchParams?.get('redirect') || '/';

  // Interactive Validation & Shake states
  const [touched, setTouched] = useState({});
  const [errors, setErrors] = useState({});
  const [shake, setShake] = useState(false);

  useEffect(() => {
    document.title = 'Register - 7th June Computers';
  }, []);

  const validateField = (fieldName, val) => {
    let err = '';
    if (!val) {
      err = 'This field is required';
    } else if (fieldName === 'name' && val.trim().length < 2) {
      err = 'Name must be at least 2 characters';
    } else if (fieldName === 'email') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(val)) {
        err = 'Please enter a valid email address';
      }
    } else if (fieldName === 'password') {
      if (val.length < 6) {
        err = 'Password must be at least 6 characters';
      }
    } else if (fieldName === 'confirm') {
      if (val !== password) {
        err = 'Passwords do not match';
      }
    }
    setErrors(prev => ({ ...prev, [fieldName]: err }));
    return err;
  };

  const handleBlur = (e) => {
    const { name: fieldName, value } = e.target;
    setTouched(prev => ({ ...prev, [fieldName]: true }));
    validateField(fieldName, value);
  };

  const handleChange = (fieldName, val) => {
    if (fieldName === 'name') setName(val);
    if (fieldName === 'email') setEmail(val);
    if (fieldName === 'password') setPassword(val);
    if (fieldName === 'confirm') setConfirm(val);
    
    if (touched[fieldName]) {
      validateField(fieldName, val);
    }
  };

  const getInputClass = (fieldName) => {
    if (!touched[fieldName]) return 'form-input';
    return errors[fieldName] ? 'form-input form-input--invalid' : 'form-input form-input--valid';
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    // Pre-submit validation
    const nameErr = validateField('name', name);
    const emailErr = validateField('email', email);
    const passErr = validateField('password', password);
    const confErr = validateField('confirm', confirm);
    
    setTouched({ name: true, email: true, password: true, confirm: true });

    if (nameErr || emailErr || passErr || confErr) {
      setShake(true);
      setTimeout(() => setShake(false), 450);
      return;
    }

    setLoading(true);
    try {
      await register(name, email, password);
      router.push(redirectTo);
    } catch (err) {
      setError(err.message || 'Registration failed. Please check your credentials.');
      setShake(true);
      setTimeout(() => setShake(false), 450);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-page">
      <div className={`auth-card ${shake ? 'form-shake' : ''}`}>
        <div className="auth-card__header">
          <div className="auth-card__logo-wrapper">
            <Link href="/" title="Go to Home">
              <img src="/images/logo.png" alt="7th June Computers Logo" className="auth-card__logo" />
            </Link>
          </div>
          <h1 className="auth-card__title">Create Account</h1>
          <p className="auth-card__subtitle">Join 7th June Computers today securely</p>
        </div>
        
        {error && (
          <div className="auth-error" role="alert" aria-live="assertive">
            {error}
          </div>
        )}
        
        {redirectTo !== '/' && (
          <div 
            style={{ 
              background: 'rgba(108,92,231,.06)', 
              padding: '12px 16px', 
              borderRadius: 'var(--radius-sm)', 
              marginBottom: '16px', 
              fontSize: '.88rem', 
              color: 'var(--primary)',
              fontWeight: 600
            }}
          >
            🔒 Create an account to complete your purchase securely
          </div>
        )}

        <form onSubmit={handleSubmit} noValidate>
          <div className="form-group">
            <label className="form-label" htmlFor="register-name">Full Name</label>
            <input 
              type="text" 
              name="name"
              className={getInputClass('name')} 
              value={name} 
              onChange={e => handleChange('name', e.target.value)} 
              onBlur={handleBlur}
              placeholder="Your full name" 
              required 
              id="register-name" 
              aria-invalid={!!errors.name}
              aria-describedby={errors.name ? 'name-error' : undefined}
            />
            {touched.name && errors.name && (
              <span className="form-error" id="name-error">{errors.name}</span>
            )}
          </div>

          <div className="form-group">
            <label className="form-label" htmlFor="register-email">Email Address</label>
            <input 
              type="email" 
              name="email"
              className={getInputClass('email')} 
              value={email} 
              onChange={e => handleChange('email', e.target.value)} 
              onBlur={handleBlur}
              placeholder="you@example.com" 
              required 
              id="register-email" 
              aria-invalid={!!errors.email}
              aria-describedby={errors.email ? 'email-error' : undefined}
            />
            {touched.email && errors.email && (
              <span className="form-error" id="email-error">{errors.email}</span>
            )}
          </div>

          <div className="form-group">
            <label className="form-label" htmlFor="register-password">Password</label>
            <input 
              type="password" 
              name="password"
              className={getInputClass('password')} 
              value={password} 
              onChange={e => handleChange('password', e.target.value)} 
              onBlur={handleBlur}
              placeholder="••••••••" 
              required 
              id="register-password" 
              aria-invalid={!!errors.password}
              aria-describedby={errors.password ? 'password-error' : undefined}
            />
            {touched.password && errors.password && (
              <span className="form-error" id="password-error">{errors.password}</span>
            )}
          </div>

          <div className="form-group">
            <label className="form-label" htmlFor="register-confirm">Confirm Password</label>
            <input 
              type="password" 
              name="confirm"
              className={getInputClass('confirm')} 
              value={confirm} 
              onChange={e => handleChange('confirm', e.target.value)} 
              onBlur={handleBlur}
              placeholder="••••••••" 
              required 
              id="register-confirm" 
              aria-invalid={!!errors.confirm}
              aria-describedby={errors.confirm ? 'confirm-error' : undefined}
            />
            {touched.confirm && errors.confirm && (
              <span className="form-error" id="confirm-error">{errors.confirm}</span>
            )}
          </div>

          <button 
            type="submit" 
            className="btn btn--primary btn--lg" 
            style={{ width: '100%' }} 
            disabled={loading} 
            id="register-submit"
          >
            {loading ? (
              <>
                <div className="spinner-sm" style={{ marginRight: '8px' }} />
                Creating Securely...
              </>
            ) : (
              'Create Account'
            )}
          </button>
        </form>
        
        <p className="auth-card__footer">
          Already have an account? <Link href={`/login${redirectTo !== '/' ? `?redirect=${encodeURIComponent(redirectTo)}` : ''}`}>Sign in</Link>
        </p>
      </div>
    </div>
  );
}
