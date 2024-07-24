import Cookies from 'js-cookie';
import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { IProfileRequest } from './models/profile/IProfileRequest';
import { IProfileResponse } from './models/profile/IProfileResponse';

export async function Profile(
  request: IProfileRequest
): Promise<IApiResponse<IProfileResponse>> {
  const client = createClient();

  const response = await client.post<IApiResponse<IProfileResponse>>(
    '/register',
    request,
    {
      headers: {
        'Content-Type': 'application/json',
      },
    }
  );
  const token = response.data; // Bu, IApiResponse<IProfileResponse> türünde olacaktır

  const setTokenCookie = (token: IApiResponse<IProfileResponse>) => {
    Cookies.set('auth_token', token.data?.token!, {
      expires: 7,
      secure: true,
      sameSite: 'strict',
    });
  };

  setTokenCookie(token);

  return response.data;
}
