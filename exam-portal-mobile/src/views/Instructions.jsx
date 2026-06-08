import { useState, useEffect } from 'react';
import { useAuth } from '../context/AuthContext';
import { useNavigate } from 'react-router-dom';
import { apiFetch } from '../utils/api';

import {
  ClipboardList,
  Clock,
  ShieldCheck,
  AlertTriangle,
  LogOut,
} from 'lucide-react';

export default function Instructions() {
  const { logout } = useAuth();
  const navigate = useNavigate();

  const [agreed, setAgreed] = useState(false);
  const [examMeta, setExamMeta] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');

  useEffect(() => {
    const fetchExamData = async () => {
      try {
        const response = await apiFetch('/getExamData');
        const data = await response.json();

        // ✨ HANDLE THE 403 & OTHER ERROR STATES EXPLICITLY
        if (!response.ok) {
          if (response.status === 403) {
            // Displays "Exam Not Started Yet" or "Exam Time Over" directly from CodeIgniter
            setError(data.message || 'The exam window is currently closed.');
          } else if (response.status === 401) {
            setError('Session expired. Please log in again.');
          } else {
            setError(data.message || 'Failed to retrieve exam records.');
          }
          setLoading(false);
          return;
        }

        if (!data.status) {
          setError(data.message || 'An unexpected error occurred.');
          setLoading(false);
          return;
        }

        setExamMeta(data.exam_meta);
      } catch (err) {
        setError('Unable to connect to server. Please check your internet connection.');
      } finally {
        setLoading(false);
      }
    };

    fetchExamData();
  }, [navigate]);

  const handleStartExam = async () => {
    if (!agreed) return;

    if (document.documentElement.requestFullscreen) {
      await document.documentElement.requestFullscreen();
    }

    navigate('/exam');
  };

  return (
    <div className="instructions-layout">
      {/* HEADER */}
      <header className="instructions-header">
        <div className="instructions-header-content">
          <div>
            <p className="page-badge">
              <ShieldCheck size={14} /> Active Examination
            </p>
            <h1 className="page-title">
              {examMeta?.title || 'Examination Gateway'}
            </h1>
          </div>
          <button onClick={logout} className="exit-button">
            <LogOut size={16} /> Exit
          </button>
        </div>
      </header>

      {/* MAIN */}
      <main className="instructions-main">
        {loading ? (
          <div className="loading-card">
            <div className="spinner"></div>
            <p>Loading exam details...</p>
          </div>
        ) : error ? (
          /* ✨ SYSTEM ERROR MESSAGES DISPLAYED UNIQUELY HERE */
          <div className="error-card flex flex-col items-center gap-2 alert-danger">
            <AlertTriangle size={24} className="text-red-500" />
            <h3 className="font-bold text-lg">Access Denied</h3>
            <p className="text-center">{error}</p>
          </div>
        ) : (
          <>
            {/* INFO GRID */}
            <div className="info-grid">
              <div className="info-card">
                <h3>
                  <ClipboardList size={18} /> Exam Rules
                </h3>
                <ul>
                  <li>No tab switching allowed</li>
                  <li>No page refresh during exam</li>
                  <li>Auto submit on timer end</li>
                </ul>
              </div>

              <div className="info-card">
                <h3>
                  <Clock size={18} /> Time & Safety
                </h3>
                <ul>
                  <li>Ensure stable internet</li>
                  <li>Do not close browser</li>
                  <li>Fullscreen mode required</li>
                </ul>
              </div>
            </div>

            {/* AGREEMENT */}
            <div className="agreement-card">
              <label className="checkbox-wrapper">
                <input
                  type="checkbox"
                  checked={agreed}
                  onChange={(e) => setAgreed(e.target.checked)}
                />
                <span>
                  I have read and agree to all exam instructions
                </span>
              </label>
            </div>
          </>
        )}
      </main>

      {/* FOOTER */}
      <footer className="instructions-footer">
        <button
          onClick={handleStartExam}
          disabled={!agreed || !!error}
          className="btn-primary start-exam-button"
        >
          Start Exam
        </button>
      </footer>
    </div>
  );
}