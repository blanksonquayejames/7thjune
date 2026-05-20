"use client";
import { createContext, useContext, useState, useEffect } from 'react';
import { loginUser, registerUser } from '../services/api';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    const saved = sessionStorage.getItem('7jc_user');
    if (saved) setUser(JSON.parse(saved));
    setLoading(false);
  }, []);

  const login = async (email, password) => {
    const u = await loginUser(email, password);
    setUser(u);
    sessionStorage.setItem('7jc_user', JSON.stringify(u));
    return u;
  };

  const register = async (name, email, password) => {
    const u = await registerUser(name, email, password);
    setUser(u);
    sessionStorage.setItem('7jc_user', JSON.stringify(u));
    return u;
  };

  const logout = () => {
    setUser(null);
    sessionStorage.removeItem('7jc_user');
  };

  const isAdmin = user?.role === 'admin';

  return (
    <AuthContext.Provider value={{ user, loading, login, register, logout, isAdmin }}>
      {children}
    </AuthContext.Provider>
  );
}

export const useAuth = () => useContext(AuthContext);
