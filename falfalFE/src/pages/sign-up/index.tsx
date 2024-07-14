import { useState } from 'react';
import { useRouter } from 'next/router';
import { ToastContainer, toast } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import Input from '../../components/basic/Input';
import SimpleLayout from '../../layouts/SimpleLayout/SimpleLayout';
import * as Styled from '../../styles/sign-up.styled';
import Image from 'next/image';
import Link from 'next/link';
import { ReactElement } from 'react';
import { register } from '../../services/register/register'; // register fonksiyonunu import edin
import { IRegisterRequest } from '../../services/register/models/register/IRegisterRequest'; // Kullanıcı giriş modelini import edin

const SignIn = () => {
  const [email, setEmail] = useState('');
  const [name, setName] = useState('');
  const [lastName, setLastName] = useState('');
  const [password, setPassword] = useState('');
  const [confirmPassword, setConfirmPassword] = useState('');
  const router = useRouter();

  const handleSubmit = async (event) => {
    event.preventDefault();

    if (password !== confirmPassword) {
      toast.error('Şifreler uyuşmuyor!');
      return;
    }

    const requestData: IRegisterRequest = {
      email,
      name,
      lastName,
      password,
    };

    try {
      const response = await register(requestData);

      toast.success(response.message || 'Kullanıcı başarıyla kaydedildi.', {
        onClose: () => router.push('/home'),
        autoClose: 5000, // 3 saniye sonra yönlendirme
      });

    } catch (error) {
      console.error('API Error:', error);
      toast.error(error.message || 'Kullanıcı kaydı sırasında bir hata oluştu.');
    }
  };

  return (
    <Card type="vertical">
      <ToastContainer />
      <form onSubmit={handleSubmit}>
        <Styled.SignUpWrapper>
          <h1>Sign up</h1>
          <Input
            type="text"
            id="email"
            name="email"
            placeholder="E-posta adresinizi giriniz"
            label="E-posta adresiniz"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
          />
          <Input
            type="text"
            id="name"
            name="name"
            placeholder="Adınızı giriniz"
            label="Adınız"
            value={name}
            onChange={(e) => setName(e.target.value)}
          />
          <Input
            type="text"
            id="lastName"
            name="lastName"
            placeholder="Soyadınızı giriniz"
            label="Soyadınız"
            value={lastName}
            onChange={(e) => setLastName(e.target.value)}
          />
          <Input
            type="password"
            id="password"
            name="password"
            placeholder="Şifrenizi giriniz"
            label="Şifre"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />
          <Input
            type="password"
            id="confirmPassword"
            name="confirmPassword"
            placeholder="Şifrenizi tekrar giriniz"
            label="Şifre (Tekrar)"
            value={confirmPassword}
            onChange={(e) => setConfirmPassword(e.target.value)}
          />
          <Button type="submit">Sign up</Button>
          <div className="seperator">
            <div className="seperator-text">or sign in using</div>
          </div>
          <Styled.GoogleLogin>
            <Image src={'/images/google.svg'} alt={''} width={24} height={24} />
            <span>Sign In with Google</span>
          </Styled.GoogleLogin>
          <Styled.AppleLogin>
            <Image src={'/images/apple.svg'} alt={''} width={24} height={24} />
            <span>Sign In with Apple</span>
          </Styled.AppleLogin>
        </Styled.SignUpWrapper>
      </form>
      <Styled.ActionLink>
        Do you already have an account?
        <Link href={'/sign-in'}>Sign In</Link>
      </Styled.ActionLink>
    </Card>
  );
};

SignIn.getLayout = (page: ReactElement) => <SimpleLayout>{page}</SimpleLayout>;

export default SignIn;
