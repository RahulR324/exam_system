import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';

import Login from './views/Login';
import Instructions from './views/Instructions';
import Exam from './views/Exam';
import Result from './views/Result';

import { ProtectedRoute, PublicRoute } from './routes/RouteGuards';

function App() {
  return (
    <Router>
      {/* AuthProvider is placed inside Router so context can safely use useNavigate handlers */}
      <AuthProvider> 
        <Routes>
          {/* Public Routing Layer - Restricts logged-in students from viewing the login screen again */}
          <Route 
            path="/" 
            element={
              <PublicRoute>
                <Login />
              </PublicRoute>
            } 
          />

          {/* Secure Routing Layer - Explicitly blocks unauthenticated traffic */}
          <Route 
            path="/instructions" 
            element={
              <ProtectedRoute>
                <Instructions />
              </ProtectedRoute>
            } 
          />
          
          <Route 
            path="/exam" 
            element={
              <ProtectedRoute>
                <Exam />
              </ProtectedRoute>
            } 
          />
          
          <Route 
            path="/result" 
            element={
              <ProtectedRoute>
                <Result />
              </ProtectedRoute>
            } 
          />

          {/* Fallback Catch-All - Safely redirects dead links back to root */}
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </AuthProvider>
    </Router>
  );
}

export default App;