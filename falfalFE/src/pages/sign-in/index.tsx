import Image from 'next/image';
import Link from 'next/link';
import { ReactElement } from 'react';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import Input from '../../components/basic/Input';
import SignInLogic from '../../hooks/SignIn.logic';
import SimpleLayout from '../../layouts/SimpleLayout/SimpleLayout';
import * as Styled from '../../styles/sign-in.styled';

const SignIn = () => {
  const { signInData, handleChange, handleSubmit, googleRegister, isLoading } = SignInLogic();
  return (
    <Card type="vertical">
      <form onSubmit={handleSubmit}>
        <Styled.SignInWrapper>
          <h1>Giriş</h1>
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
          <Button type="submit" loading={isLoading} disabled={isLoading}>
            Giriş Yap
          </Button>
          <div className="seperator">
            <div className="seperator-text">Veya</div>
          </div>
          <Styled.GoogleLogin onClick={googleRegister}>
            <Image src={'/images/google.svg'} alt={''} width={24} height={24} />
            <span>Google İle Giriş Yap</span>
          </Styled.GoogleLogin>

        </Styled.SignInWrapper>
      </form>
      <Styled.ActionLink>
        Henüz hesabınız yok mu?{' '}
        <Link href={'/sign-up'}><b>Kayıt Ol</b></Link>
      </Styled.ActionLink>
    </Card>
  );
};

SignIn.getLayout = (page: ReactElement) => <SimpleLayout>{page}</SimpleLayout>;

export default SignIn;
