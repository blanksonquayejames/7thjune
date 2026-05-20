import { useEffect, useRef } from 'react';
import { BsExclamationTriangleFill, BsQuestionCircleFill, BsBoxArrowRight, BsTrashFill, BsX } from 'react-icons/bs';
import './ConfirmationModal.css';

export default function ConfirmationModal({ isOpen, options, onClose, onConfirm }) {
  const modalRef = useRef(null);

  useEffect(() => {
    if (isOpen) {
      document.body.style.overflow = 'hidden';
      // Focus modal container
      if (modalRef.current) {
        modalRef.current.focus();
      }
    } else {
      document.body.style.overflow = '';
    }
    return () => {
      document.body.style.overflow = '';
    };
  }, [isOpen]);

  if (!isOpen || !options) return null;

  const {
    title = 'Are you sure?',
    message = 'Please confirm this action.',
    confirmText = 'Confirm',
    cancelText = 'Cancel',
    type = 'primary' // 'primary' | 'danger' | 'warning'
  } = options;

  const getIcon = () => {
    switch (type) {
      case 'danger':
        return <div className="confirm-icon confirm-icon--danger"><BsTrashFill /></div>;
      case 'warning':
        return <div className="confirm-icon confirm-icon--warning"><BsExclamationTriangleFill /></div>;
      case 'logout':
        return <div className="confirm-icon confirm-icon--logout"><BsBoxArrowRight /></div>;
      default:
        return <div className="confirm-icon confirm-icon--primary"><BsQuestionCircleFill /></div>;
    }
  };

  const handleKeyDown = (e) => {
    if (e.key === 'Escape') {
      onClose();
    }
  };

  return (
    <div 
      className="confirm-overlay" 
      onClick={onClose}
      role="presentation"
    >
      <div 
        ref={modalRef}
        className="confirm-card" 
        onClick={e => e.stopPropagation()}
        onKeyDown={handleKeyDown}
        tabIndex="-1"
        role="dialog"
        aria-modal="true"
        aria-labelledby="confirm-title"
        aria-describedby="confirm-message"
      >
        <button className="confirm-close-btn" onClick={onClose} aria-label="Close dialog">
          <BsX />
        </button>

        <div className="confirm-body">
          {getIcon()}
          <h3 id="confirm-title" className="confirm-title">{title}</h3>
          <p id="confirm-message" className="confirm-message">{message}</p>
        </div>

        <div className="confirm-actions">
          <button 
            className="btn btn--outline" 
            onClick={onClose}
          >
            {cancelText}
          </button>
          <button 
            className={`btn ${type === 'danger' || type === 'logout' ? 'btn--danger' : 'btn--primary'}`}
            onClick={onConfirm}
            autoFocus
          >
            {confirmText}
          </button>
        </div>
      </div>
    </div>
  );
}
