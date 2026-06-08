import { useState, useEffect, useCallback, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import { apiFetch } from '../utils/api';

function MathQuestionRenderer({ htmlContent }) {
  const containerRef = useRef(null);

  useEffect(() => {
    if (
      containerRef.current &&
      typeof window.renderMathInElement === 'function'
    ) {
      window.renderMathInElement(containerRef.current, {
        delimiters: [
          { left: '$$', right: '$$', display: true },
          { left: '$', right: '$', display: false },
          { left: '\\(', right: '\\)', display: false },
          { left: '\\[', right: '\\]', display: true }
        ],
        throwOnError: false,
      });
    }
  }, [htmlContent]);

  return (
    <div
      className="exam-question-text"
      ref={containerRef}
      dangerouslySetInnerHTML={{ __html: htmlContent }}
    />
  );
}

export default function Exam() {
  const navigate = useNavigate();

  const [questions, setQuestions] = useState([]);
  const [currentIndex, setCurrentIndex] = useState(0);
  const [answers, setAnswers] = useState({});
  const [timeLeft, setTimeLeft] = useState(null);

  const [examMeta, setExamMeta] = useState({});
  const [studentInfo, setStudentInfo] = useState({});

  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [submitting, setSubmitting] = useState(false);

  const isSubmittingRef = useRef(false);

  // FETCH EXAM DATA
  useEffect(() => {
    const fetchExamData = async () => {
      try {
        const res = await apiFetch('/getExamData');
        const data = await res.json();

        const formattedQuestions = (data.questions || []).map(q => ({
          id: q.id,
          text: q.text,
          options: q.options || []
        }));

        setQuestions(formattedQuestions);

        setExamMeta(data.exam_meta || {});

        if (data.student) {
          setStudentInfo({
            name: data.student.name || 'N/A',
            registerNumber:
              data.student.registerNumber ||
              data.student.register_number ||
              'N/A'
          });
        }

        setTimeLeft(data.exam_meta?.duration_seconds || 3600);

      } catch (err) {
        console.error(err);
        setError('Server not reachable');
      } finally {
        setLoading(false);
      }
    };

    fetchExamData();
  }, []);

  // AUTO SUBMIT FUNCTION
  const handleAutoSubmit = useCallback(async () => {
    if (isSubmittingRef.current) return;

    try {
      isSubmittingRef.current = true;
      setSubmitting(true);

      const formData = new URLSearchParams();

      formData.append('submit_exam', '1');

      questions.forEach(q => {
        formData.append(q.id, answers[q.id] || '');
      });

      const res = await apiFetch('/exam', {
        method: 'POST',
        body: formData
      });

      const data = await res.json();

      navigate('/result', {
        state: {
          score: data.score || 0,
          total: questions.length
        }
      });

    } catch (err) {
      console.error(err);
      alert('Failed to submit exam');
    }
  }, [answers, questions, navigate]);

  // TIMER
  useEffect(() => {
    if (timeLeft === null || submitting) return;

    if (timeLeft <= 0) {
      handleAutoSubmit();
      return;
    }

    const timer = setInterval(() => {
      setTimeLeft(prev => prev - 1);
    }, 1000);

    return () => clearInterval(timer);

  }, [timeLeft, submitting, handleAutoSubmit]);

  const current = questions[currentIndex];

  const progress =
    questions.length > 0
      ? ((currentIndex + 1) / questions.length) * 100
      : 0;

  const minutes = Math.floor((timeLeft || 0) / 60);
  const seconds = (timeLeft || 0) % 60;

  // LOADING SCREEN
  if (loading) {
    return (
      <div className="exam-loading">
        <div className="spinner"></div>
        Loading Exam...
      </div>
    );
  }

  // ERROR SCREEN
  if (error) {
    return (
      <div className="exam-error">
        {error}
      </div>
    );
  }

  return (
    <div className="exam-page">

      {/* TOP BAR */}
      <header className="exam-topbar">

        <div>
          <h1>{examMeta.title || 'Online Exam'}</h1>

          <p>
            {studentInfo?.name || 'Unknown Student'} (
            {studentInfo?.registerNumber || 'N/A'})
          </p>
        </div>

        <div className="exam-timer">
          ⏱ {String(minutes).padStart(2, '0')}:
          {String(seconds).padStart(2, '0')}
        </div>

      </header>

      {/* PROGRESS BAR */}
      <div className="exam-progress">
        <div style={{ width: `${progress}%` }} />
      </div>

      {/* MAIN CONTENT */}
      <main className="exam-container">

        <div className="exam-card">

          <div className="exam-q-header">
            Question {currentIndex + 1} / {questions.length}
          </div>

          <MathQuestionRenderer
            htmlContent={current?.text || ''}
          />

          <div className="exam-options">

            {current?.options?.map((opt, i) => (

              <button
                key={i}
                type="button"
                className={`exam-option ${
                  answers[current.id] === opt ? 'active' : ''
                }`}
                onClick={() =>
                  setAnswers(prev => ({
                    ...prev,
                    [current.id]: opt
                  }))
                }
              >
                {String.fromCharCode(65 + i)}. {opt}
              </button>

            ))}

          </div>

        </div>

        {/* NAVIGATION */}
        <div className="exam-nav">

          <button
            disabled={currentIndex === 0}
            onClick={() =>
              setCurrentIndex(prev => prev - 1)
            }
          >
            Previous
          </button>

          {currentIndex < questions.length - 1 ? (

            <button
              onClick={() =>
                setCurrentIndex(prev => prev + 1)
              }
            >
              Next
            </button>

          ) : (

            <button
              className="submit"
              onClick={handleAutoSubmit}
              disabled={submitting}
            >
              {submitting ? 'Submitting...' : 'Submit Exam'}
            </button>

          )}

        </div>

      </main>

    </div>
  );
}