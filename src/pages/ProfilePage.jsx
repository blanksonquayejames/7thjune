import { useState } from 'react';
import { Link } from 'react-router-dom';
import { BsChevronRight } from 'react-icons/bs';
import { useAuth } from '../context/AuthContext';

export default function ProfilePage() {
  const { user, logout } = useAuth();
  const [name, setName] = useState(user?.name || '');
  const [email] = useState(user?.email || '');
  const [saved, setSaved] = useState(false);

  document.title = 'Profile - 7th June Computers';

  const handleSave = (e) => {
    e.preventDefault();
    setSaved(true);
    setTimeout(() => setSaved(false), 2000);
  };

  return (
    <>
      <div className="page-header"><div className="container"><h1 className="page-header__title">Profile</h1><div className="page-header__breadcrumb"><Link to="/">Home</Link><BsChevronRight style={{ fontSize: '.7rem' }} /><span>Profile</span></div></div></div>
      <section className="section">
        <div className="container" style={{ maxWidth: '600px' }}>
          <div className="card" style={{ padding: '40px' }}>
            <h3 style={{ fontWeight: 700, marginBottom: '24px' }}>Account Information</h3>
            <form onSubmit={handleSave}>
              <div className="form-group"><label className="form-label">Name</label><input className="form-input" value={name} onChange={e => setName(e.target.value)} /></div>
              <div className="form-group"><label className="form-label">Email</label><input className="form-input" value={email} disabled style={{ opacity: .6 }} /></div>
              <div className="form-group"><label className="form-label">Role</label><input className="form-input" value={user?.role || ''} disabled style={{ opacity: .6, textTransform: 'capitalize' }} /></div>
              <button type="submit" className="btn btn--primary" id="save-profile-btn">{saved ? 'Saved!' : 'Save Changes'}</button>
            </form>
          </div>
          <div className="card" style={{ padding: '40px', marginTop: '24px', borderColor: 'rgba(239,68,68,.2)' }}>
            <h3 style={{ fontWeight: 700, marginBottom: '12px', color: 'var(--danger)' }}>Danger Zone</h3>
            <p style={{ color: 'var(--gray)', fontSize: '.9rem', marginBottom: '16px' }}>Logging out will end your session.</p>
            <button className="btn btn--danger" onClick={logout}>Logout</button>
          </div>
        </div>
      </section>
    </>
  );
}
