import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { IProfileRequest } from './models/profile/IProfileRequest';
import { IProfileResponse } from './models/profile/IProfileResponse';

export async function getProfile(): Promise<IApiResponse<IProfileResponse>> {
  const client = createClient();

  const response = await client.get<IApiResponse<IProfileResponse>>('/profile');
  return response.data;
}

export async function setProfile(
  request: IProfileRequest
): Promise<IApiResponse<IProfileResponse>> {
  const client = createClient();

  const response = await client.post<IApiResponse<IProfileResponse>>(
    '/profile/update',
    request,
    {
      headers: {
        'Content-Type': 'application/json',
      },
    }
  );

  return response.data;
}
