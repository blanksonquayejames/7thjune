import { useState, useEffect } from 'react';
import { BsCheckCircleFill, BsXCircleFill, BsInfoCircleFill, BsExclamationCircleFill, BsX } from 'react-icons/bs';
import './ToastContainer.css';

function ToastItem({ toast, onRemove }) {
  const [isExiting, setIsExiting] = useState(false);
  const [progress, setProgress] = useState(100);

  useEffect(() => {
    const startTime = Date.now();
    const endTime = startTime + toast.duration;

    const interval = setInterval(() => {
      const now = Date.now();
      const remaining = Math.max(0, endTime - now);
      const percentage = (remaining / toast.duration) * 100;
      setProgress(percentage);
      if (percentage <= 0) {
        clearInterval(interval);
      }
    }, 16); // ~60fps

    const timer = setTimeout(() => {
      setIsExiting(true);
      setTimeout(() => {
        onRemove(toast.id);
      }, 300); // duration of exit animation
    }, toast.duration);

    return () => {
      clearInterval(interval);
      clearTimeout(timer);
    };
  }, [toast, onRemove]);

  const handleClose = () => {
    setIsExiting(true);
    setTimeout(() => {
      onRemove(toast.id);
    }, 300);
  };

  const getIcon = () => {
    switch (toast.type) {
      case 'success':
        return <BsCheckCircleFill className="toast-item__icon toast-item__icon--success" />;
      case 'error':
        return <BsXCircleFill className="toast-item__icon toast-item__icon--error" />;
      case 'warning':
        return <BsExclamationCircleFill className="toast-item__icon toast-item__icon--warning" />;
      case 'info':
      default:
        return <BsInfoCircleFill className="toast-item__icon toast-item__icon--info" />;
    }
  };

  return (
    <div className={`toast-item toast-item--${toast.type} ${isExiting ? 'toast-item--exiting' : 'toast-item--entering'}`}>
      <div className="toast-item__body">
        {getIcon()}
        <span className="toast-item__message">{toast.message}</span>
        <button onClick={handleClose} className="toast-item__close-btn" aria-label="Close notification">
          <BsX />
        </button>
      </div>
      <div className="toast-item__progress-bar">
        <div 
          className={`toast-item__progress-fill toast-item__progress-fill--${toast.type}`} 
          style={{ width: `${progress}%` }} 
        />
      </div>
    </div>
  );
}

export default function ToastContainer({ toasts, removeToast }) {
  return (
    <div className="toast-deck" id="toast-notifications-deck">
      {toasts.map(toast => (
        <ToastItem key={toast.id} toast={toast} onRemove={removeToast} />
      ))}
    </div>
  );
}
