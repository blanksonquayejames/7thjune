import { useState, useEffect } from 'react';
import Link from 'next/link';
import { useRouter, useSearchParams } from 'next/navigation';
import { useAuth } from '../context/AuthContext';
import './AuthPages.css';

export default function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const { login } = useAuth();
  const router = useRouter();
  const searchParams = useSearchParams();
  const redirectTo = searchParams?.get('redirect') || '/';

  // Interactive Validation & Shake states
  const [touched, setTouched] = useState({});
  const [errors, setErrors] = useState({});
  const [shake, setShake] = useState(false);

  useEffect(() => {
    document.title = 'Sign In - 7th June Computers';
  }, []);

  const validateField = (name, val) => {
    let err = '';
    if (!val) {
      err = 'This field is required';
    } else if (name === 'email') {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(val)) {
        err = 'Please enter a valid email address';
      }
    } else if (name === 'password' && val.length < 6) {
      err = 'Password must be at least 6 characters';
    }
    setErrors(prev => ({ ...prev, [name]: err }));
    return err;
  };

  const handleBlur = (e) => {
    const { name, value } = e.target;
    setTouched(prev => ({ ...prev, [name]: true }));
    validateField(name, value);
  };

  const handleChange = (name, val) => {
    if (name === 'email') setEmail(val);
    if (name === 'password') setPassword(val);
    if (touched[name]) {
      validateField(name, val);
    }
  };

  const getInputClass = (name) => {
    if (!touched[name]) return 'form-input';
    return errors[name] ? 'form-input form-input--invalid' : 'form-input form-input--valid';
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    // Pre-submit validation
    const emailErr = validateField('email', email);
    const passErr = validateField('password', password);
    setTouched({ email: true, password: true });

    if (emailErr || passErr) {
      setShake(true);
      setTimeout(() => setShake(false), 450);
      return;
    }

    setLoading(true);
    try {
      await login(email, password);
      router.push(redirectTo);
    } catch (err) {
      setError('Unable to authenticate. Please verify your credentials and try again.');
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
          <h1 className="auth-card__title">Welcome Back</h1>
          <p className="auth-card__subtitle">Sign in to your account securely</p>
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
            🔒 Please sign in to complete your purchase securely
          </div>
        )}

        <form onSubmit={handleSubmit} noValidate>
          <div className="form-group">
            <label className="form-label" htmlFor="login-email">Email Address</label>
            <input 
              type="email" 
              name="email"
              className={getInputClass('email')} 
              value={email} 
              onChange={e => handleChange('email', e.target.value)} 
              onBlur={handleBlur}
              placeholder="you@example.com" 
              required 
              id="login-email" 
              aria-invalid={!!errors.email}
              aria-describedby={errors.email ? 'email-error' : undefined}
            />
            {touched.email && errors.email && (
              <span className="form-error" id="email-error">{errors.email}</span>
            )}
          </div>

          <div className="form-group">
            <label className="form-label" htmlFor="login-password">Password</label>
            <input 
              type="password" 
              name="password"
              className={getInputClass('password')} 
              value={password} 
              onChange={e => handleChange('password', e.target.value)} 
              onBlur={handleBlur}
              placeholder="••••••••" 
              required 
              id="login-password" 
              aria-invalid={!!errors.password}
              aria-describedby={errors.password ? 'password-error' : undefined}
            />
            {touched.password && errors.password && (
              <span className="form-error" id="password-error">{errors.password}</span>
            )}
          </div>

          <button 
            type="submit" 
            className="btn btn--primary btn--lg" 
            style={{ width: '100%' }} 
            disabled={loading} 
            id="login-submit"
          >
            {loading ? (
              <>
                <div className="spinner-sm" style={{ marginRight: '8px' }} />
                Processing Securely...
              </>
            ) : (
              'Sign In'
            )}
          </button>
        </form>
        
        <p className="auth-card__footer">
          Don't have an account? <Link href={`/register${redirectTo !== '/' ? `?redirect=${encodeURIComponent(redirectTo)}` : ''}`}>Create one</Link>
        </p>
      </div>
    </div>
  );
}
