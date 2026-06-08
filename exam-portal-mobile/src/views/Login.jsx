import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { apiFetch } from '../utils/api';

export default function Login() {
  const { login } = useAuth();

  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');

    const cleanUsername = username.trim();
    const cleanPassword = password.trim();

    if (!cleanUsername || !cleanPassword) {
      setError('Please fill in all fields.');
      return;
    }

    setLoading(true);

    try {
      const formData = new FormData();
      formData.append('username', cleanUsername);
      formData.append('password', cleanPassword);

      const response = await apiFetch('/login', {
        method: 'POST',
        body: formData,
      });

      const data = await response.json();

      if (response.ok && data.status && data.token) {
        localStorage.setItem(
          'studentInfo',
          JSON.stringify({
            name: data.name || '',
            registerNumber: data.registerNumber || '',
          })
        );

        login(data.token);
      } else {
        setError(data.message || 'Invalid credentials');
      }
    } catch (err) {
      setError('Unable to connect to server.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-wrapper">
      {/* BACKGROUND CARD LAYER */}
      <div className="login-card">

        {/* HEADER */}
        <div className="login-header">
          <div className="login-badge">ONLINE EXAMINATION</div>

          <h1 className="login-title">
            Welcome Back
          </h1>

          <p className="login-subtitle">
            Sign in to access your exam dashboard
          </p>
        </div>

        {/* ERROR */}
        {error && (
          <div className="login-error">
            ⚠ {error}
          </div>
        )}

        {/* FORM */}
        <form onSubmit={handleSubmit} className="login-form">

          <div className="form-group">
            <label>Username</label>
            <input
              type="text"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              placeholder="Enter your username"
              autoComplete="username"
            />
          </div>

          <div className="form-group">
            <label>Password</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              placeholder="Enter your password"
              autoComplete="current-password"
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="btn-primary login-submit-btn"
          >
            {loading ? 'Signing In...' : 'Login to Continue'}
          </button>

        </form>

        {/* FOOTER TEXT */}
        <div className="login-footer">
          Secure access • Student examination portal
        </div>

      </div>
    </div>
  );
}