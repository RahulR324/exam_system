import { useEffect, useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

// Simple inline SVG icons (no dependency)
const CheckIcon = () => (
  <svg width="64" height="64" viewBox="0 0 24 24" fill="none">
    <path
      d="M20 6L9 17l-5-5"
      stroke="#22c55e"
      strokeWidth="2.5"
      strokeLinecap="round"
      strokeLinejoin="round"
    />
  </svg>
);

const LogoutIcon = () => (
  <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
    <path
      d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"
      stroke="currentColor"
      strokeWidth="2"
    />
    <path
      d="M16 17l5-5-5-5"
      stroke="currentColor"
      strokeWidth="2"
    />
    <path
      d="M21 12H9"
      stroke="currentColor"
      strokeWidth="2"
    />
  </svg>
);

export default function Result() {
  const { logout } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();

  const score = location.state?.score || 0;
  const total = location.state?.total || 0;

  const [countdown, setCountdown] = useState(5);

  useEffect(() => {
    const interval = setInterval(() => {
      setCountdown(prev => {
        if (prev <= 1) {
          clearInterval(interval);
          logout();
          navigate('/');
          return 0;
        }
        return prev - 1;
      });
    }, 1000);

    return () => clearInterval(interval);
  }, [logout, navigate]);

  const percentage =
    total > 0 ? Math.round((score / total) * 100) : 0;

  return (
    <div className="result-page">

      <div className="result-container">

        {/* Success Icon */}
        <div className="result-icon">
          <CheckIcon />
        </div>

        <h1 className="result-title">
          Exam Submitted Successfully
        </h1>

        <p className="result-subtitle">
          Your responses have been recorded and evaluated.
        </p>

        {/* SCORE CARD */}
        <div className="result-score-card">

          <div className="score-label">Final Score</div>

          <div className="score-value">
            {score}
            <span>/ {total}</span>
          </div>

          <div className="score-percentage">
            {percentage}% Performance
          </div>

        </div>

        {/* COUNTDOWN */}
        <div className="result-countdown">
          <p>Redirecting to login in</p>
          <div className="countdown-number">{countdown}</div>
        </div>

        {/* BUTTON */}
        <button onClick={logout} className="result-btn">
          <LogoutIcon />
          <span>Return to Login</span>
        </button>

      </div>

    </div>
  );
}