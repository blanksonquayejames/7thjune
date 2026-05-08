import { useState, useEffect } from 'react';
import { BsPlus, BsPencil, BsTrash, BsX } from 'react-icons/bs';
import { getCategories, createCategory, updateCategory, deleteCategory } from '../../services/api';
import './AdminPages.css';

export default function AdminCategoriesPage() {
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');

  const [modal, setModal] = useState(null); // 'add' | 'edit' | 'delete'
  const [form, setForm] = useState({ name: '' });
  const [selected, setSelected] = useState(null);

  const load = () => {
    setLoading(true);
    getCategories().then(setCategories).finally(() => setLoading(false));
  };

  useEffect(() => { document.title = 'Categories - Admin'; load(); }, []);

  const closeModal = () => { setModal(null); setSelected(null); setForm({ name: '' }); setError(''); };

  const openAdd = () => { setForm({ name: '' }); setModal('add'); };
  const openEdit = (c) => { setSelected(c); setForm({ name: c.name }); setModal('edit'); };
  const openDelete = (c) => { setSelected(c); setModal('delete'); };

  const handleSave = async (e) => {
    e.preventDefault();
    if (!form.name.trim()) { setError('Category name is required.'); return; }
    setError('');
    setSaving(true);
    try {
      if (modal === 'add') {
        const created = await createCategory(form);
        setCategories(prev => [...prev, created]);
      } else {
        const updated = await updateCategory(selected.id, form);
        setCategories(prev => prev.map(c => c.id === selected.id ? updated : c));
      }
      closeModal();
    } catch (err) {
      setError(err.message);
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async () => {
    setSaving(true);
    setError('');
    try {
      await deleteCategory(selected.id);
      setCategories(prev => prev.filter(c => c.id !== selected.id));
      closeModal();
    } catch (err) {
      setError(err.message);
    } finally {
      setSaving(false);
    }
  };

  if (loading) return <div className="loading-screen"><div className="spinner" /></div>;

  return (
    <div>
      <div className="admin-header-row">
        <h1 className="admin-page-title">Categories ({categories.length})</h1>
        <button className="btn btn--primary btn--sm" onClick={openAdd} id="add-category-btn"><BsPlus /> Add Category</button>
      </div>

      <table className="admin-table">
        <thead><tr><th>Name</th><th>Slug</th><th>Products</th><th>Actions</th></tr></thead>
        <tbody>
          {categories.map(c => (
            <tr key={c.id}>
              <td><strong>{c.name}</strong></td>
              <td style={{ color: 'var(--gray)' }}>{c.slug}</td>
              <td>{c.products_count}</td>
              <td>
                <div className="admin-actions">
                  <button className="admin-action-btn admin-action-btn--edit" onClick={() => openEdit(c)} title="Edit"><BsPencil /></button>
                  <button className="admin-action-btn admin-action-btn--delete" onClick={() => openDelete(c)} title="Delete"><BsTrash /></button>
                </div>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* ── Add / Edit Modal ── */}
      {(modal === 'add' || modal === 'edit') && (
        <div className="modal-overlay" onClick={closeModal}>
          <div className="modal modal--sm" onClick={e => e.stopPropagation()}>
            <div className="modal__header">
              <h2 className="modal__title">{modal === 'add' ? 'Add New Category' : `Edit: ${selected?.name}`}</h2>
              <button className="modal__close" onClick={closeModal}><BsX /></button>
            </div>
            <form onSubmit={handleSave}>
              <div className="modal__body">
                {error && <div className="auth-error" style={{ marginBottom: '16px' }}>{error}</div>}
                <div className="form-group">
                  <label className="form-label">Category Name *</label>
                  <input className="form-input" value={form.name} onChange={e => setForm({ ...form, name: e.target.value })} placeholder="e.g. Smart Watches" required autoFocus />
                </div>
                <p style={{ fontSize: '.82rem', color: 'var(--gray)' }}>
                  Slug will be auto-generated: <strong style={{ color: 'var(--dark-3)' }}>{form.name ? form.name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') : '...'}</strong>
                </p>
              </div>
              <div className="modal__footer">
                <button type="button" className="btn btn--outline btn--sm" onClick={closeModal}>Cancel</button>
                <button type="submit" className="btn btn--primary btn--sm" disabled={saving}>{saving ? 'Saving...' : (modal === 'add' ? 'Create Category' : 'Save Changes')}</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* ── Delete Confirmation Modal ── */}
      {modal === 'delete' && selected && (
        <div className="modal-overlay" onClick={closeModal}>
          <div className="modal modal--sm" onClick={e => e.stopPropagation()}>
            <div className="modal__header">
              <h2 className="modal__title">Delete Category</h2>
              <button className="modal__close" onClick={closeModal}><BsX /></button>
            </div>
            <div className="modal__body">
              {error && <div className="auth-error" style={{ marginBottom: '16px' }}>{error}</div>}
              <div className="delete-confirm">
                <div className="delete-confirm__icon">⚠️</div>
                <p className="delete-confirm__text">
                  Are you sure you want to delete<br /><span className="delete-confirm__name">{selected.name}</span>?
                </p>
                {selected.products_count > 0 && (
                  <p style={{ fontSize: '.85rem', color: 'var(--danger)', fontWeight: 600, marginTop: '8px' }}>
                    ⚠️ This category has {selected.products_count} product{selected.products_count > 1 ? 's' : ''}. Remove or reassign them first.
                  </p>
                )}
              </div>
            </div>
            <div className="modal__footer">
              <button className="btn btn--outline btn--sm" onClick={closeModal}>Cancel</button>
              <button className="btn btn--danger btn--sm" onClick={handleDelete} disabled={saving}>{saving ? 'Deleting...' : 'Delete Category'}</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
