import Cookies from "js-cookie";
import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { ILoginRequest } from './models/login/ILoginRequest';
import { ILoginResponse } from './models/login/ILoginResponse';
import {IRegisterResponse} from "@/services/register/models/register/IRegisterResponse";


export async function Login(
  request: ILoginRequest
): Promise<IApiResponse<ILoginResponse>> {
  const client = createClient();

  const response = await client.post<IApiResponse<IRegisterResponse>>(
        '/login',
        request,
        {
            headers: {
                'Content-Type': 'application/json',
            },
        }
    );
    const token = response.data; // Bu, IApiResponse<IRegisterResponse> türünde olacaktır

    const setTokenCookie = (token: IApiResponse<IRegisterResponse>) => {
        Cookies.set('auth_token', token.data?.token!, {
            expires: 7,
            secure: true,
            sameSite: 'strict',
        });
    };

    setTokenCookie(token);

    return response.data;
}
