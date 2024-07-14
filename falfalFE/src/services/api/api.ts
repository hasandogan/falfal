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

export function createClient() {
  let client = axios.create({
    baseURL: process.env.NEXT_PUBLIC_API_URL,
  });

  client = withAuth(client, cookie.get('Token'));

  return client;
}
