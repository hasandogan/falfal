'use client';
import { useRouter } from 'next/router';
import { useState } from 'react';
import { toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { Login } from '../services/login/login';
import { ILoginRequest } from '../services/login/models/login/ILoginRequest';
import { setTokenCookie } from '../utils/helpers/setTokenCookie';

const SignInLogic = () => {
  const router = useRouter();
  const initialForm = {
    email: '',
    password: '',
  };

  const [signInData, setSignInData] = useState(initialForm);

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setSignInData((prevState) => ({ ...prevState, [name]: value }));
  };

  const handleSubmit = (event: React.FormEvent) => {
    event.preventDefault();
    loginRequest();
  };

  const loginRequest = async () => {
    const requestData: ILoginRequest = signInData;
    try {
      const response = await Login(requestData);
      if (response?.token) {
        setTokenCookie(response?.token);
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
  };

  return {
    signInData,
    handleChange,
    handleSubmit,
  };
};

export default SignInLogic;
