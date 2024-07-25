import Cookies from "js-cookie";
import { createClient } from '../api/api';
import { IApiResponse } from '../api/models/IApiResponse';
import { ILoginRequest } from './models/login/ILoginRequest';
import { ILoginResponse } from './models/login/ILoginResponse';
import { IRegisterResponse } from '@/services/register/models/register/IRegisterResponse';
import { toast } from "react-toastify";

export async function Login(
    request: ILoginRequest
): Promise<IApiResponse<ILoginResponse>> {
    const client = createClient();

    try {
        const response = await client.post<IApiResponse<IRegisterResponse>>(
            '/login',
            request,
            {
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );
        const token = response.data.token; // Bu, IApiResponse<IRegisterResponse> türünde olacaktır

        if (token) {
            Cookies.set('auth_token', token, {
                expires: 7,
                secure: true,
                sameSite: 'strict',
            });
        } else {
            throw new Error('Token alınamadı.');
        }
        return response.data;
    } catch (error: any) {
        toast.error('Giriş yapılırken bir hata oluştu. Lütfen tekrar deneyin.');
        throw error;
    }
}
