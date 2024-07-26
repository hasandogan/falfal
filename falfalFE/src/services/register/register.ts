import { setTokenCookie } from '../../utils/helpers/setTokenCookie';
import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { IRegisterRequest } from './models/register/IRegisterRequest';
import { IRegisterResponse } from './models/register/IRegisterResponse';

export async function Register(
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

  if (response?.data?.token) {
    setTokenCookie(response?.data?.token);
  }

  return response.data;
}
