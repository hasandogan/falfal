import { useSearchParams } from "next/navigation";
import { createClient } from "@/services/api/api";
import { IApiResponse } from "@/services/api/models/IApiResponse";
import { ILoginGoogleRequest } from "@/services/login/models/google/ILoginGoogleRequest";
import { useEffect } from "react";
import { toast, ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css'; // Stilleri de ekleyin
import { useRouter } from 'next/router';
import {setTokenCookie} from "@/utils/helpers/setTokenCookie";

const Check = () => {
    const searchParams = useSearchParams();
    const router = useRouter();

    useEffect(() => {
        const fetchData = async () => {
            const client = createClient();
            const code = searchParams.get('code');
            if (!code) {
                return;
            }
            const requestData: ILoginGoogleRequest = {
                "code": code
            };
            const response = await client.post<IApiResponse<ILoginGoogleRequest>>(
                `/connect/google/check`,
                requestData
            );
            if (!response.data.token) {
                toast.error(response.data.message || 'Kullanıcı Kaydederken bir sorunla karşılaştık istersen başka bir yöntem dene', {
                    onClose: () => router.push('/home'),
                    autoClose: 5000,
                });
            }else{
                setTokenCookie(response?.data.token);
                toast.success( 'Hazırlan seni içeri alıyorum!', {
                    onClose: () => router.push('/home'),
                    autoClose: 1500,
                });
            }
            return response.data;
        };
        fetchData();
    }, [searchParams]);

    return (
        <>
            <ToastContainer />
        </>
    );
}

export default Check;
