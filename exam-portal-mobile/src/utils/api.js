const BASE_URL = 'http://localhost:8080';

export const apiFetch = async (endpoint, options = {}) => {
  const token = localStorage.getItem('auth_token');
  
  const headers = {
    'X-Requested-With': 'XMLHttpRequest',
    ...options.headers,
  };

  // Only add Content-Type if not FormData or URLSearchParams
  if (options.body && !(options.body instanceof FormData) && !(options.body instanceof URLSearchParams)) {
    headers['Content-Type'] = 'application/json';
  }

  // Safe checks for presence of token strings
  if (token && token !== 'undefined' && token !== 'null' && token.trim() !== '') {
    headers['Authorization'] = `Bearer ${token}`;
  }

  try {
    const response = await fetch(`${BASE_URL}${endpoint}`, {
      ...options,
      headers,
      credentials: 'include',
    });

    console.log(`📡 ${options.method || 'GET'} ${endpoint} → ${response.status}`);
    return response;
  } catch (error) {
    console.error('❌ Network Error:', error);
    
    // Fallback response mock for local connection drops
    const errorResponse = {
      ok: false,
      status: 0,
      statusText: 'Network Error',
      json: async () => ({ 
        status: false, 
        message: 'Cannot connect to server. Please verify XAMPP Apache is running on port 8080.' 
      }),
      text: async () => 'Network Error',
      clone: function() { return this; },
      headers: new Headers(),
      redirected: false,
      type: 'error',
      url: `${BASE_URL}${endpoint}`,
    };
    
    return errorResponse;
  }
};

export const parseResponse = async (response) => {
  try {
    return await response.json();
  } catch (error) {
    console.error('❌ Failed to parse JSON:', error);
    return { status: false, error: 'Failed to parse JSON response' };
  }
};