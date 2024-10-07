import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { ISendCoffeeRequest } from './models/ISendCoffeeRequest';
import { ISendCoffeeResponse } from './models/ISendCoffeeResponse';

export async function sendCoffee(
    request: { images: File[] }
): Promise<IApiResponse<ISendCoffeeResponse>> {
  const client = createClient();

  const response = await client.post<IApiResponse<ISendCoffeeResponse>>(
    '/coffee/process/start',
    request
  );

  return response.data;
}
