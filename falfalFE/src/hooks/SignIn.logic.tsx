'use client';
import { useRouter } from 'next/router';
import { useState } from 'react';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { Login } from '../services/login/login';
import { ILoginRequest } from '../services/login/models/login/ILoginRequest';
import { setTokenCookie } from '../utils/helpers/setTokenCookie';
import {createClient} from "@/services/api/api";
import {IApiResponse} from "@/services/api/models/IApiResponse";
import {ILoginGoogleRequest} from "@/services/login/models/google/ILoginGoogleRequest";

const SignInLogic = () => {
  const router = useRouter();
  const initialForm = {
    email: '',
    password: '',
  };

  const [signInData, setSignInData] = useState(initialForm);
  const [isLoading, setIsLoading] = useState<boolean>(false);

  const googleRegister = async ()  => {
    const client =  createClient()
    const response = await client.get<IApiResponse<ILoginGoogleRequest>>(
        `/connect/google`
    );
    window.location.href = response.data.redirect
  };
  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setSignInData((prevState) => ({ ...prevState, [name]: value }));
  };

  const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault();
    if (signInData.email === '' || signInData.password === '') {
      toast.error('Email adresi ve şifre alanı boş bırakılamaz.');
      return;
    }
    loginRequest();
  };

  const loginRequest = async () => {
    setIsLoading(true);
    const requestData: ILoginRequest = signInData;
    try {
      const response = await Login(requestData);
      if (response?.token) {
        setTokenCookie(response?.token);
      }

      if (response?.status === 401){
        toast.error(response.message);
        return;
      }

      toast.success(
        response.message ||
          'Harika! her şey doğru görnüyor şimdi falına bakabilmek için seni içeri alıyorum.',
        {
          onClose: () => router.push('/home'),
          autoClose: 5000,
        }
      );
      setSignInData(initialForm);
    } catch (error: any) {
      toast.error(
        error.message || 'Kullanıcı kaydı sırasında bir hata oluştu.'
      );
    }
    setIsLoading(false);
  };

  return {
    signInData,
    handleChange,
    handleSubmit,
    googleRegister,
    isLoading,
  };
};

export default SignInLogic;
