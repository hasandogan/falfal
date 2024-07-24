import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { ILoginRequest } from './models/login/ILoginRequest';
import { ILoginResponse } from './models/login/ILoginResponse';

export async function Login(
  request: ILoginRequest
): Promise<IApiResponse<ILoginResponse>> {
  const client = createClient();

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
}
