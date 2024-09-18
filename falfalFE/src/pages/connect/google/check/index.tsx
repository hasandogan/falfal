import {useSearchParams} from "next/navigation";
import {createClient} from "@/services/api/api";
import {IApiResponse} from "@/services/api/models/IApiResponse";
import {ILoginGoogleRequest} from "@/services/login/models/google/ILoginGoogleRequest";
import {useEffect} from "react";

const Check = () => {
    const searchParams = useSearchParams();
    useEffect(() => {
        const fetchData = async () => {
            const client = createClient();
            const code = searchParams.get('code');
            if (!code) {
                return;
            }
            const requestData: ILoginGoogleRequest = {
                "code" : searchParams.get('code')
            };
            const response = await client.post<IApiResponse<ILoginGoogleRequest>>(
                `/connect/google/check`,
                requestData
            );
            return response.data
        }
        fetchData()
    }, [searchParams]);

}
export default Check;
