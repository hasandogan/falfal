import Image from 'next/image';
import Link from 'next/link';
import { ReactElement } from 'react';
import 'react-toastify/dist/ReactToastify.css';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import Input from '../../components/basic/Input';
import SignUpLogic from '../../hooks/SignUp.logic';
import SimpleLayout from '../../layouts/SimpleLayout/SimpleLayout';
import * as Styled from '../../styles/sign-up.styled';

const SignUp = () => {
  const { signUpData, handleSubmit, handleChange, googleRegister ,isLoading } = SignUpLogic();
  return (
    <Card type="vertical">
      <form onSubmit={handleSubmit}>
        <Styled.SignUpWrapper>
          <h1>Kayıt Ol</h1>
          <Input
            type="text"
            id="email"
            name="email"
            placeholder="E-posta adresinizi giriniz"
            label="E-posta adresiniz"
            value={signUpData.email}
            onChange={handleChange}
          />
          <Input
            type="text"
            id="name"
            name="name"
            placeholder="Adınızı giriniz"
            label="Adınız"
            value={signUpData.name}
            onChange={handleChange}
          />
          <Input
            type="text"
            id="lastName"
            name="lastName"
            placeholder="Soyadınızı giriniz"
            label="Soyadınız"
            value={signUpData.lastName}
            onChange={handleChange}
          />
          <Input
            type="password"
            id="password"
            name="password"
            placeholder="Şifrenizi giriniz"
            label="Şifre"
            value={signUpData.password}
            onChange={handleChange}
          />
          <Input
            type="password"
            id="confirmPassword"
            name="confirmPassword"
            placeholder="Şifrenizi tekrar giriniz"
            label="Şifre (Tekrar)"
            value={signUpData.confirmPassword}
            onChange={handleChange}
          />
          <Button type="submit" loading={isLoading} disabled={isLoading}>
            Kayıt Ol
          </Button>
          <div className="seperator">
            <div className="seperator-text">Veya</div>
          </div>
        </Styled.SignUpWrapper>
      </form>
      <Styled.ActionLink>
        Zaten bir hesabınız var mı?{' '}
        <Link href={'/sign-in'}><b>Giriş Yap</b></Link>
      </Styled.ActionLink>
    </Card>
  );
};

SignUp.getLayout = (page: ReactElement) => <SimpleLayout>{page}</SimpleLayout>;

export default SignUp;
