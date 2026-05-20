import { useState, useEffect, useRef } from 'react';
import { BsPlus, BsPencil, BsTrash, BsPercent, BsX, BsImage, BsCloudUpload } from 'react-icons/bs';
import { getAllProducts, getCategories, createProduct, updateProduct, deleteProduct, updateProductDiscount } from '../../services/api';
import { formatPrice, hasActiveDiscount, getDiscountedPrice } from '../../utils/helpers';
import './AdminPages.css';

const emptyProduct = {
  name: '', category_id: '', price: '', stock: '', description: '',
  is_active: true, is_hot: false, is_featured: false,
  discount_percentage: 0, discount_start: '', discount_end: '',
};

const emptyDiscount = { discount_percentage: '', discount_start: '', discount_end: '' };

export default function AdminProductsPage() {
  const [products, setProducts] = useState([]);
  const [categories, setCategories] = useState([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');

  // Modals
  const [modal, setModal] = useState(null); // 'add' | 'edit' | 'delete' | 'discount'
  const [form, setForm] = useState({ ...emptyProduct });
  const [discountForm, setDiscountForm] = useState({ ...emptyDiscount });
  const [selected, setSelected] = useState(null);
  const [imagePreview, setImagePreview] = useState(null);
  const fileInputRef = useRef(null);

  const load = () => {
    setLoading(true);
    Promise.all([getAllProducts(), getCategories()])
      .then(([prods, cats]) => { setProducts(prods); setCategories(cats); })
      .finally(() => setLoading(false));
  };

  useEffect(() => { document.title = 'Products - Admin'; load(); }, []);

  const closeModal = () => { setModal(null); setSelected(null); setForm({ ...emptyProduct }); setDiscountForm({ ...emptyDiscount }); setError(''); setImagePreview(null); };

  const openAdd = () => { setForm({ ...emptyProduct }); setModal('add'); };

  const openEdit = (p) => {
    setSelected(p);
    setForm({
      name: p.name, category_id: p.category_id, price: p.price, stock: p.stock,
      description: p.description, is_active: p.is_active, is_hot: p.is_hot, is_featured: p.is_featured,
      discount_percentage: p.discount_percentage || 0,
      discount_start: p.discount_start ? p.discount_start.split('T')[0] : '',
      discount_end: p.discount_end ? p.discount_end.split('T')[0] : '',
    });
    setImagePreview(p.image || null);
    setModal('edit');
  };

  const openDelete = (p) => { setSelected(p); setModal('delete'); };

  const openDiscount = (p) => {
    setSelected(p);
    setDiscountForm({
      discount_percentage: p.discount_percentage || '',
      discount_start: p.discount_start ? p.discount_start.split('T')[0] : '',
      discount_end: p.discount_end ? p.discount_end.split('T')[0] : '',
    });
    setModal('discount');
  };

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target;
    setForm(f => ({ ...f, [name]: type === 'checkbox' ? checked : value }));
  };

  const handleImageUpload = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    if (!file.type.startsWith('image/')) { setError('Please select a valid image file.'); return; }
    if (file.size > 5 * 1024 * 1024) { setError('Image must be smaller than 5MB.'); return; }
    const url = URL.createObjectURL(file);
    setImagePreview(url);
    setForm(f => ({ ...f, image: url }));
  };

  const handleImageDrop = (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files?.[0];
    if (file) {
      const input = fileInputRef.current;
      const dt = new DataTransfer();
      dt.items.add(file);
      input.files = dt.files;
      handleImageUpload({ target: input });
    }
  };

  const removeImage = () => {
    setImagePreview(null);
    setForm(f => ({ ...f, image: null }));
    if (fileInputRef.current) fileInputRef.current.value = '';
  };

  const handleDiscountChange = (e) => {
    const { name, value } = e.target;
    setDiscountForm(f => ({ ...f, [name]: value }));
  };

  const handleSave = async (e) => {
    e.preventDefault();
    if (!form.name || !form.category_id || !form.price) { setError('Name, category, and price are required.'); return; }
    setError('');
    setSaving(true);
    try {
      if (modal === 'add') {
        const created = await createProduct(form);
        setProducts(prev => [created, ...prev]);
      } else {
        const updated = await updateProduct(selected.id, form);
        setProducts(prev => prev.map(p => p.id === selected.id ? updated : p));
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
    try {
      await deleteProduct(selected.id);
      setProducts(prev => prev.filter(p => p.id !== selected.id));
      closeModal();
    } catch (err) {
      setError(err.message);
    } finally {
      setSaving(false);
    }
  };

  const handleDiscountSave = async (e) => {
    e.preventDefault();
    setSaving(true);
    setError('');
    try {
      const updated = await updateProductDiscount(selected.id, discountForm);
      setProducts(prev => prev.map(p => p.id === selected.id ? updated : p));
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
        <h1 className="admin-page-title">Products ({products.length})</h1>
        <button className="btn btn--primary btn--sm" onClick={openAdd} id="add-product-btn"><BsPlus /> Add Product</button>
      </div>

      <table className="admin-table">
        <thead>
          <tr><th>Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Discount</th><th>Status</th><th>Actions</th></tr>
        </thead>
        <tbody>
          {products.map(p => (
            <tr key={p.id}>
              <td><strong>{p.name}</strong></td>
              <td>{p.category?.name}</td>
              <td>
                {hasActiveDiscount(p)
                  ? <><span style={{ color: 'var(--primary)', fontWeight: 700 }}>{formatPrice(getDiscountedPrice(p))}</span> <span style={{ textDecoration: 'line-through', color: 'var(--gray-light)', fontSize: '.82rem' }}>{formatPrice(p.price)}</span></>
                  : formatPrice(p.price)
                }
              </td>
              <td><span style={{ color: p.stock > 0 ? 'var(--success)' : 'var(--danger)', fontWeight: 600 }}>{p.stock}</span></td>
              <td>
                {hasActiveDiscount(p)
                  ? <span className="discount-info">-{p.discount_percentage}%</span>
                  : <span style={{ color: 'var(--gray-light)', fontSize: '.82rem' }}>None</span>
                }
              </td>
              <td>{p.is_active ? <span className="badge badge--delivered">Active</span> : <span className="badge badge--cancelled">Inactive</span>}</td>
              <td>
                <div className="admin-actions">
                  <button className="admin-action-btn admin-action-btn--edit" onClick={() => openEdit(p)} title="Edit"><BsPencil /></button>
                  <button className="admin-action-btn admin-action-btn--discount" onClick={() => openDiscount(p)} title="Discount"><BsPercent /></button>
                  <button className="admin-action-btn admin-action-btn--delete" onClick={() => openDelete(p)} title="Delete"><BsTrash /></button>
                </div>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* ── Add / Edit Modal ── */}
      {(modal === 'add' || modal === 'edit') && (
        <div className="modal-overlay" onClick={closeModal}>
          <div className="modal modal--lg" onClick={e => e.stopPropagation()}>
            <div className="modal__header">
              <h2 className="modal__title">{modal === 'add' ? 'Add New Product' : `Edit: ${selected?.name}`}</h2>
              <button className="modal__close" onClick={closeModal}><BsX /></button>
            </div>
            <form onSubmit={handleSave}>
              <div className="modal__body">
                {error && <div className="auth-error" style={{ marginBottom: '16px' }}>{error}</div>}

                <div className="form-group">
                  <label className="form-label">Product Name *</label>
                  <input name="name" className="form-input" value={form.name} onChange={handleChange} placeholder="e.g. Gaming Laptop Pro" required />
                </div>

                <div className="form-row">
                  <div className="form-group">
                    <label className="form-label">Category *</label>
                    <select name="category_id" className="form-select" value={form.category_id} onChange={handleChange} required>
                      <option value="">Select category</option>
                      {categories.map(c => <option key={c.id} value={c.id}>{c.name}</option>)}
                    </select>
                  </div>
                  <div className="form-group">
                    <label className="form-label">Price (₵) *</label>
                    <input name="price" type="number" step="0.01" min="0" className="form-input" value={form.price} onChange={handleChange} placeholder="0.00" required />
                  </div>
                </div>

                <div className="form-group">
                  <label className="form-label">Stock Quantity</label>
                  <input name="stock" type="number" min="0" className="form-input" value={form.stock} onChange={handleChange} placeholder="0" style={{ maxWidth: '200px' }} />
                </div>

                <div className="form-group">
                  <label className="form-label">Product Image</label>
                  <input type="file" accept="image/*" ref={fileInputRef} onChange={handleImageUpload} style={{ display: 'none' }} id="product-image-input" />
                  {imagePreview ? (
                    <div className="image-upload-preview">
                      <img src={imagePreview} alt="Preview" className="image-upload-preview__img" />
                      <div className="image-upload-preview__actions">
                        <button type="button" className="btn btn--outline btn--sm" onClick={() => fileInputRef.current?.click()}><BsImage /> Change Image</button>
                        <button type="button" className="btn btn--ghost btn--sm" style={{ color: 'var(--danger)' }} onClick={removeImage}><BsX /> Remove</button>
                      </div>
                    </div>
                  ) : (
                    <div className="image-upload-dropzone" onClick={() => fileInputRef.current?.click()} onDragOver={e => e.preventDefault()} onDrop={handleImageDrop}>
                      <BsCloudUpload className="image-upload-dropzone__icon" />
                      <p className="image-upload-dropzone__text">Click to upload or drag and drop</p>
                      <p className="image-upload-dropzone__hint">PNG, JPG, WEBP up to 5MB</p>
                    </div>
                  )}
                </div>

                <div className="form-group">
                  <label className="form-label">Description</label>
                  <textarea name="description" className="form-input" rows={3} value={form.description} onChange={handleChange} placeholder="Product description..." style={{ resize: 'vertical' }} />
                </div>

                <div className="toggle-row">
                  <label className="toggle-item">
                    <input type="checkbox" name="is_active" checked={form.is_active} onChange={handleChange} />
                    Active
                  </label>
                  <label className="toggle-item">
                    <input type="checkbox" name="is_hot" checked={form.is_hot} onChange={handleChange} />
                    Hot 🔥
                  </label>
                  <label className="toggle-item">
                    <input type="checkbox" name="is_featured" checked={form.is_featured} onChange={handleChange} />
                    Featured ⭐
                  </label>
                </div>
              </div>
              <div className="modal__footer">
                <button type="button" className="btn btn--outline btn--sm" onClick={closeModal}>Cancel</button>
                <button type="submit" className="btn btn--primary btn--sm" disabled={saving}>{saving ? 'Saving...' : (modal === 'add' ? 'Create Product' : 'Save Changes')}</button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* ── Discount Modal ── */}
      {modal === 'discount' && selected && (
        <div className="modal-overlay" onClick={closeModal}>
          <div className="modal modal--sm" onClick={e => e.stopPropagation()}>
            <div className="modal__header">
              <h2 className="modal__title">Manage Discount</h2>
              <button className="modal__close" onClick={closeModal}><BsX /></button>
            </div>
            <form onSubmit={handleDiscountSave}>
              <div className="modal__body">
                <p style={{ fontSize: '.9rem', color: 'var(--gray)', marginBottom: '20px' }}>
                  Set discount for <strong style={{ color: 'var(--dark)' }}>{selected.name}</strong> (current price: {formatPrice(selected.price)})
                </p>
                {error && <div className="auth-error" style={{ marginBottom: '16px' }}>{error}</div>}

                <div className="form-group">
                  <label className="form-label">Discount Percentage (%)</label>
                  <input name="discount_percentage" type="number" min="0" max="100" step="1" className="form-input" value={discountForm.discount_percentage} onChange={handleDiscountChange} placeholder="e.g. 15" />
                </div>

                <div className="form-row">
                  <div className="form-group">
                    <label className="form-label">Start Date</label>
                    <input name="discount_start" type="date" className="form-input" value={discountForm.discount_start} onChange={handleDiscountChange} />
                  </div>
                  <div className="form-group">
                    <label className="form-label">End Date</label>
                    <input name="discount_end" type="date" className="form-input" value={discountForm.discount_end} onChange={handleDiscountChange} />
                  </div>
                </div>

                {discountForm.discount_percentage > 0 && (
                  <div style={{ padding: '16px', background: 'var(--light-2)', borderRadius: 'var(--radius-sm)', marginTop: '8px' }}>
                    <span style={{ fontSize: '.85rem', color: 'var(--gray)' }}>Preview: </span>
                    <span style={{ fontWeight: 700, color: 'var(--primary)' }}>{formatPrice(selected.price * (1 - discountForm.discount_percentage / 100))}</span>
                    <span style={{ textDecoration: 'line-through', color: 'var(--gray-light)', fontSize: '.85rem', marginLeft: '8px' }}>{formatPrice(selected.price)}</span>
                    <span style={{ color: 'var(--success)', fontSize: '.85rem', marginLeft: '8px' }}>Save {formatPrice(selected.price * discountForm.discount_percentage / 100)}</span>
                  </div>
                )}

                <button type="button" className="btn btn--ghost btn--sm" style={{ marginTop: '12px', color: 'var(--danger)' }} onClick={() => setDiscountForm({ discount_percentage: 0, discount_start: '', discount_end: '' })}>
                  Remove Discount
                </button>
              </div>
              <div className="modal__footer">
                <button type="button" className="btn btn--outline btn--sm" onClick={closeModal}>Cancel</button>
                <button type="submit" className="btn btn--accent btn--sm" disabled={saving}>{saving ? 'Saving...' : 'Apply Discount'}</button>
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
              <h2 className="modal__title">Delete Product</h2>
              <button className="modal__close" onClick={closeModal}><BsX /></button>
            </div>
            <div className="modal__body">
              {error && <div className="auth-error" style={{ marginBottom: '16px' }}>{error}</div>}
              <div className="delete-confirm">
                <div className="delete-confirm__icon">⚠️</div>
                <p className="delete-confirm__text">
                  Are you sure you want to delete<br /><span className="delete-confirm__name">{selected.name}</span>?
                </p>
                <p style={{ fontSize: '.82rem', color: 'var(--gray)' }}>This action cannot be undone.</p>
              </div>
            </div>
            <div className="modal__footer">
              <button className="btn btn--outline btn--sm" onClick={closeModal}>Cancel</button>
              <button className="btn btn--danger btn--sm" onClick={handleDelete} disabled={saving}>{saving ? 'Deleting...' : 'Delete Product'}</button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
