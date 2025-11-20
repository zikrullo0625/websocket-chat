import axios from 'axios';

const apiClient = axios.create({
    baseURL: '/api',
    headers: {
        'Accept': 'application/json',
    },
});

apiClient.interceptors.request.use(config => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
}, error => {
    return Promise.reject(error);
});

const api = {
    get(url, config = {}) {
        return apiClient.get(url, config).then(res => res.data);
    },
    post(url, data = {}, config = {}) {
        return apiClient.post(url, data, config).then(res => res.data);
    },
    put(url, data = {}, config = {}) {
        return apiClient.put(url, data, config).then(res => res.data);
    },
    delete(url, config = {}) {
        return apiClient.delete(url, config).then(res => res.data);
    }
};

export default api;
