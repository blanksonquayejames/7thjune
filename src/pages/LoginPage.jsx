import { useState } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import './AuthPages.css';

export default function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const { login } = useAuth();
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const redirectTo = searchParams.get('redirect') || '/';

  document.title = 'Sign In - 7th June Computers';

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setLoading(true);
    try {
      await login(email, password);
      navigate(redirectTo);
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="auth-page">
      <div className="auth-card">
        <div className="auth-card__header">
          <h1 className="auth-card__title">Welcome Back</h1>
          <p className="auth-card__subtitle">Sign in to your account</p>
        </div>
        {error && <div className="auth-error">{error}</div>}
        {redirectTo !== '/' && (
          <div style={{ background: 'rgba(108,92,231,.06)', padding: '12px 16px', borderRadius: 'var(--radius-sm)', marginBottom: '16px', fontSize: '.88rem', color: 'var(--primary)' }}>
            🔒 Please sign in to continue to checkout
          </div>
        )}
        <form onSubmit={handleSubmit}>
          <div className="form-group">
            <label className="form-label">Email</label>
            <input type="email" className="form-input" value={email} onChange={e => setEmail(e.target.value)} placeholder="you@example.com" required id="login-email" />
          </div>
          <div className="form-group">
            <label className="form-label">Password</label>
            <input type="password" className="form-input" value={password} onChange={e => setPassword(e.target.value)} placeholder="••••••••" required id="login-password" />
          </div>
          <button type="submit" className="btn btn--primary btn--lg" style={{ width: '100%' }} disabled={loading} id="login-submit">
            {loading ? 'Signing in...' : 'Sign In'}
          </button>
        </form>
        <p className="auth-card__footer">
          Don't have an account? <Link to="/register">Create one</Link>
        </p>
        <div className="auth-demo">
          <p><strong>Demo accounts:</strong></p>
          <p>Admin: admin@7thjunecomputers.com / password</p>
          <p>Customer: user@7thjunecomputers.com / password</p>
        </div>
      </div>
    </div>
  );
}
