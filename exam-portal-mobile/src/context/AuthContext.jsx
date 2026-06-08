import { createContext, useContext, useState, useEffect, useCallback } from 'react';
import { useNavigate } from 'react-router-dom';

const AuthContext = createContext();

export const AuthProvider = ({ children }) => {
  const navigate = useNavigate();
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const [loading, setLoading] = useState(true);

  const isValidToken = (token) => {
    return token && token !== 'undefined' && token !== 'null' && token.trim() !== '';
  };

  useEffect(() => {
    const token = localStorage.getItem('auth_token');
    if (isValidToken(token)) {
      setIsAuthenticated(true);
      console.log('✅ Valid auth token found on app start');
    } else {
      localStorage.removeItem('auth_token');
      setIsAuthenticated(false);
      console.log('⚠️ No valid token found');
    }
    setLoading(false);
  }, []);

  const login = useCallback((token) => {
    if (!isValidToken(token)) {
      console.error('❌ Invalid token provided to login function');
      return false;
    }
    
    localStorage.setItem('auth_token', token);
    setIsAuthenticated(true);
    console.log('✅ Login successful. Redirecting to instructions...');
    navigate('/instructions');
    return true;
  }, [navigate]);

  const logout = useCallback(() => {
    localStorage.removeItem('auth_token');
    localStorage.removeItem('studentInfo');
    setIsAuthenticated(false);
    console.log('✅ Logout successful. Redirecting to login...');
    navigate('/');
  }, [navigate]);

  return (
    <AuthContext.Provider value={{ login, logout, isAuthenticated, loading }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('❌ useAuth must be used within AuthProvider');
  }
  return context;
};