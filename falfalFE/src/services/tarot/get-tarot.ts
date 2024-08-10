import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { IGetTarotRequest } from './models/IGetTarotRequest';
import { IGetTarotResponse } from './models/IGetTarotResponse';

export async function GetTarot(
  request: IGetTarotRequest
): Promise<IApiResponse<IGetTarotResponse>> {
  const client = createClient();

  const response = await client.get<IApiResponse<IGetTarotResponse>>(
    `/tarots/${request.id}`
  );

  return response.data;
}
