import { useRouter } from 'next/router';
import { useState } from 'react';
import { toast } from 'react-toastify';
import { Login } from '../../services/login/login';
import { ILoginRequest } from '../../services/login/models/login/ILoginRequest';

const useSignInLogic = () => {
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

  const handleSubmit = async (event: React.FormEvent) => {
    event.preventDefault();
    const requestData: ILoginRequest = signInData;
    try {
      const response = await Login(requestData);
      toast.success(response.message || 'Bilgiler doğru yönlendiriliyorsunuz', {
        onClose: () => router.push('/home'),
        autoClose: 5000,
      });
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

export default useSignInLogic;
