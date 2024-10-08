import type { AxiosInstance, AxiosRequestConfig } from 'axios';
import axios from 'axios';
import cookie from 'js-cookie';

export interface CustomAxiosConfig extends Omit<AxiosRequestConfig, 'headers'> {
  headers?: any;
}

export function withAuth(instance: AxiosInstance, token?: string) {
  const modifiedInstance = instance;
  if (token) {
    modifiedInstance.defaults.headers.common.Authorization = `Bearer ${token}`;
  }

  return modifiedInstance;
}

export function withIpAddress(instance: AxiosInstance, ipAddress?: string) {
  const modifiedInstance = instance;
  if (ipAddress) {
    modifiedInstance.defaults.headers.common.IpAddress = ipAddress;
  }

  return modifiedInstance;
}

// Cookie'den auth_token'i almak için yardımcı fonksiyon
function getCookie(name: string) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop()?.split(';').shift();
}

// Axios örneğini oluşturun
const apiClient = axios.create({
  baseURL: process.env.API_BASE_URL || 'http://104.155.84.37:3000', // Base URL'i projenize göre ayarlayın
  headers: {
    'Content-Type': 'application/json',
  },
});

// Interceptor tanımlayın
apiClient.interceptors.request.use(
  (config) => {
    const authToken = getCookie('auth_token'); // auth_token'i cookie'den al
    if (authToken) {
      config.headers['Authorization'] = `Bearer ${authToken}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export function createClient() {
  let client = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
  });

  client = withAuth(client, cookie.get('auth_token'));

  return client;
}
