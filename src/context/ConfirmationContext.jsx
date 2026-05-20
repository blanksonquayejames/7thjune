"use client";
import { createContext, useContext, useState, useCallback, useRef } from 'react';
import ConfirmationModal from '../components/common/ConfirmationModal';

const ConfirmationContext = createContext(null);

export function ConfirmationProvider({ children }) {
  const [isOpen, setIsOpen] = useState(false);
  const [options, setOptions] = useState(null);
  const resolverRef = useRef(null);

  const confirm = useCallback((confirmOptions) => {
    setOptions(confirmOptions);
    setIsOpen(true);
    return new Promise((resolve) => {
      resolverRef.current = resolve;
    });
  }, []);

  const handleConfirm = useCallback(() => {
    setIsOpen(false);
    if (resolverRef.current) {
      resolverRef.current(true);
    }
  }, []);

  const handleClose = useCallback(() => {
    setIsOpen(false);
    if (resolverRef.current) {
      resolverRef.current(false);
    }
  }, []);

  return (
    <ConfirmationContext.Provider value={confirm}>
      {children}
      <ConfirmationModal 
        isOpen={isOpen}
        options={options}
        onClose={handleClose}
        onConfirm={handleConfirm}
      />
    </ConfirmationContext.Provider>
  );
}

export const useConfirm = () => {
  const context = useContext(ConfirmationContext);
  if (!context) {
    throw new Error('useConfirm must be used within a ConfirmationProvider');
  }
  return context;
};
