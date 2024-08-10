import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { IDashboardResponse } from './models/IDashboardResponse';

export async function Dashboard(): Promise<IApiResponse<IDashboardResponse>> {
  const client = createClient();

  const response =
    await client.get<IApiResponse<IDashboardResponse>>('/dashboard');

  return response.data;
}
