'use client';
import { IRegisterRequest } from '@/services/register/models/register/IRegisterRequest';
import { useRouter } from 'next/router';
import React, { useState } from 'react';
import { toast } from 'react-toastify';

import { Register } from '@/services/register/register';
import {createClient} from "@/services/api/api";
import {IApiResponse} from "@/services/api/models/IApiResponse";
import {ILoginGoogleRequest} from "@/services/login/models/google/ILoginGoogleRequest";

const SignUpLogic = () => {
  const router = useRouter();
  const initialForm = {
    email: '',
    name: '',
    lastName: '',
    password: '',
    confirmPassword: '',
  };
  const [signUpData, setSignUpData] = useState(initialForm);
  const [isLoading, setIsLoading] = useState<boolean>(false);

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setSignUpData((prevState) => ({ ...prevState, [name]: value }));
  };

  const handleSubmit = async (event: React.FormEvent) => {
    event.preventDefault();
    if (
      signUpData.email === '' ||
      signUpData.name === '' ||
      signUpData.lastName === '' ||
      signUpData.password === '' ||
      signUpData.confirmPassword === ''
    ) {
      toast.error('Tüm form alanlarını doldurmalısınız!');
      return;
    }
    if (signUpData.password !== signUpData.confirmPassword) {
      toast.error('Şifreler uyuşmuyor!');
      return;
    }
    setIsLoading(true);
    const requestData: IRegisterRequest = signUpData;
    try {
      const response = await Register(requestData);

      if (response?.status === 400){
        toast.error(response.message);
      }
      toast.success(response.message || 'Kullanıcı başarıyla kaydedildi.', {
        onClose: () => router.push('/home'),
        autoClose: 5000,
      });
    } catch (error: any) {
      console.error('API Error:', error);
      toast.error(
        error.message || 'Kullanıcı kaydı sırasında bir hata oluştu.'
      );
    }
    setIsLoading(false);
  };

  const googleRegister = async ()  => {
    const client =  createClient()
    const response = await client.get<IApiResponse<ILoginGoogleRequest>>(
        `/connect/google`
    );
    window.location.href = response.data.redirect
  };

  return { signUpData, handleSubmit, handleChange, googleRegister, isLoading };


};

export default SignUpLogic;
