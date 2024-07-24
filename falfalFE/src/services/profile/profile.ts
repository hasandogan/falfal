import apiClient from '@/services/api/api';
import { IApiResponse, IProfileResponse } from './models/profile/IProfileResponse';
import { IProfileRequest } from './models/profile/IProfileRequest';

// Profil verilerini POST ile güncelleyen fonksiyon
export const Profile = (profileData: IProfileRequest) => {
    return apiClient.post<IApiResponse<IProfileResponse>>('/profile', profileData);
};

// Profil verilerini GET ile alan fonksiyon
export const getProfile = () => {
    return apiClient.get<IApiResponse<IProfileResponse>>('/profile');
};
