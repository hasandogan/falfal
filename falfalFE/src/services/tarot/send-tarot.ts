import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { ISendTarotRequest } from './models/ISendTarotRequest';
import { ISendTarotResponse } from './models/ISendTarotResponse';

export async function SendTarot(
  request: ISendTarotRequest
): Promise<IApiResponse<ISendTarotResponse>> {
  const client = createClient();

  const response = await client.post<IApiResponse<ISendTarotResponse>>(
    '/sendtarot',
    request
  );

  return response.data;
}
