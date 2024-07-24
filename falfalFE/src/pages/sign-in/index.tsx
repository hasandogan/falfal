import Image from 'next/image';
import Link from 'next/link';
import { ReactElement } from 'react';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import Input from '../../components/basic/Input';
import SimpleLayout from '../../layouts/SimpleLayout/SimpleLayout';
import * as Styled from '../../styles/sign-in.styled';
import useSignInLogic from './useSignInLogic';

const SignIn = () => {
  const { signInData, handleChange, handleSubmit } = useSignInLogic();
  return (
    <Card type="vertical">
      <form onSubmit={handleSubmit}>
        <Styled.SignInWrapper>
          <h1>Sign in</h1>
          <Input
            type="text"
            id="email"
            name="email"
            placeholder="Email adresinizi giriniz"
            label="Email adresi"
            value={signInData.email}
            onChange={handleChange}
          />
          <Input
            type="password"
            id="password"
            name="password"
            placeholder="Şifrenizi giriniz"
            label="Şifre"
            value={signInData.password}
            onChange={handleChange}
          />
          <Button type="submit">Sign in</Button>
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
        </Styled.SignInWrapper>
      </form>
      <Styled.ActionLink>
        Don’t have an account?
        <Link href={'/sign-up'}>Sign Up</Link>
      </Styled.ActionLink>
    </Card>
  );
};

SignIn.getLayout = (page: ReactElement) => <SimpleLayout>{page}</SimpleLayout>;

export default SignIn;
