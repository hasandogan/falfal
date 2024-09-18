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
          <h1>Sign up</h1>
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
            Sign up
          </Button>
          <div className="seperator">
            <div className="seperator-text">or sign in using</div>
          </div>
          <Styled.GoogleLogin onClick={googleRegister}>
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

SignUp.getLayout = (page: ReactElement) => <SimpleLayout>{page}</SimpleLayout>;

export default SignUp;
