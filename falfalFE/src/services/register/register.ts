import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { IRegisterRequest } from './models/register/IRegisterRequest';
import { IRegisterResponse } from './models/register/IRegisterResponse';
import axios from 'axios';
import Cookies from 'js-cookie';

export async function register(
    request: IRegisterRequest
): Promise<IApiResponse<IRegisterResponse>> {
  const client = createClient();

  const response = await client.post<IApiResponse<IRegisterResponse>>(
      '/register',
      request,
      {
        headers: {
          'Content-Type': 'application/json',
        },
      }
  );
  const token = response.data; // Bu, IApiResponse<IRegisterResponse> türünde olacaktır

  const setTokenCookie = (token: IApiResponse<IRegisterResponse>) => {
    Cookies.set('auth_token', token.token, { expires: 7, secure: true, sameSite: 'strict' });
  };

  setTokenCookie(token);

  return response.data;

}
