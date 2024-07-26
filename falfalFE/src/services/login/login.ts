import { toast } from 'react-toastify';
import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { ILoginRequest } from './models/login/ILoginRequest';
import { ILoginResponse } from './models/login/ILoginResponse';

export async function Login(
  request: ILoginRequest
): Promise<IApiResponse<ILoginResponse>> {
  const client = createClient();

  try {
    const response = await client.post<IApiResponse<ILoginResponse>>(
      '/login',
      request,
      {
        headers: {
          'Content-Type': 'application/json',
        },
      }
    );

    return response.data;
  } catch (error: any) {
    toast.error('Giriş yapılırken bir hata oluştu. Lütfen tekrar deneyin.');
    throw error;
  }
}
