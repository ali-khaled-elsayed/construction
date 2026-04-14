import axios from 'axios';

const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';

export const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Request interceptor to add auth token
api.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Response interceptor for error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('token');
      window.location.href = '/auth/login';
    }
    return Promise.reject(error);
  }
);

// Auth API
export const authAPI = {
  register: (data: { name: string; email: string; password: string; password_confirmation: string; role: string }) =>
    api.post('/auth/register', data),
  login: (data: { email: string; password: string }) =>
    api.post('/auth/login', data),
  logout: () => api.post('/auth/logout'),
  me: () => api.get('/auth/me'),
};

// Location API
export const locationAPI = {
  getCountries: (lang?: string) => api.get('/countries', { params: { lang } }),
  getCountry: (code: string, lang?: string) => api.get(`/countries/${code}`, { params: { lang } }),
  getCities: (params?: { country?: string; lang?: string }) => api.get('/cities', { params }),
  getCity: (id: number) => api.get(`/cities/${id}`),
};

// Job API
export const jobAPI = {
  getJobRequests: (params?: any) => api.get('/job-requests', { params }),
  getJobRequest: (id: number) => api.get(`/job-requests/${id}`),
  createJobRequest: (data: any) => api.post('/job-requests', data),
};

export default api;