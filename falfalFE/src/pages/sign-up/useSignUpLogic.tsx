import { useRouter } from 'next/router';
import { useState } from 'react';
import { toast } from 'react-toastify';
import { IRegisterRequest } from '../../services/register/models/register/IRegisterRequest';

import { Register } from '@/services/register/register';

const useSignUpLogic = () => {
  const router = useRouter();
  const initialForm = {
    email: '',
    name: '',
    lastName: '',
    password: '',
    confirmPassword: '',
  };
  const [signUpData, setSignUpData] = useState(initialForm);

  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>
  ) => {
    const { name, value } = e.target;
    setSignUpData((prevState) => ({ ...prevState, [name]: value }));
  };

  const handleSubmit = async (event: React.FormEvent) => {
    event.preventDefault();

    if (initialForm.password !== initialForm.confirmPassword) {
      toast.error('Şifreler uyuşmuyor!');
      return;
    }

    const requestData: IRegisterRequest = signUpData;

    try {
      const response = await Register(requestData);

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
  };
  return { signUpData, handleSubmit, handleChange };
};

export default useSignUpLogic;
