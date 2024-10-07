import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { IGetCoffeeRequest } from './models/IGetCoffeeRequest';
import { IGetCoffeeResponse } from './models/IGetCoffeeResponse';

export async function GetCoffee(
  request: IGetCoffeeRequest
): Promise<IApiResponse<IGetCoffeeResponse>> {
  const client = createClient();

  const response = await client.get<IApiResponse<IGetCoffeeResponse>>(
    `/coffee/${request.id}`
  );

  return response.data;
}
