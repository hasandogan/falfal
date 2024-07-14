import { useState } from 'react';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import Input from '../../components/basic/Input';
import SimpleLayout from '../../layouts/SimpleLayout/SimpleLayout';
import * as Styled from '../../styles/sign-in.styled';
import Image from 'next/image';
import Link from 'next/link';
import { ReactElement } from 'react';
import { login } from '../../services/login/login';
import { ILoginRequest } from '../../services/login/models/login/ILoginRequest';
import Cookies from 'js-cookie';

const SignIn = () => {
  // Form verilerini saklayacak state'leri tanımlayın
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  // Form gönderildiğinde çağrılacak fonksiyon
  const handleSubmit = async (event) => {
    event.preventDefault();

    // Kullanıcı giriş bilgilerini bir objeye toplayın
    const requestData: ILoginRequest = {
      username: username,
      password: password,
    };

    try {
      // API isteğini yapın
      const response = await login(requestData);

      // API yanıtını konsola yazdırın (opsiyonel)
      console.log('API Response:', response);

      // Başarılı işlem mesajını gösterin (opsiyonel)
      alert('Giriş başarıyla yapıldı.');

      // Formu sıfırlayın veya diğer işlemleri yapın (opsiyonel)
      setUsername('');
      setPassword('');

    } catch (error) {

      // Hata durumunda konsola yazdırın (opsiyonel)
      console.error('API Error:', error);

      // Hata mesajını kullanıcıya gösterin (opsiyonel)
      alert('Giriş sırasında bir hata oluştu.');
    }
  };

  return (
    <Card type="vertical">
      <form onSubmit={handleSubmit}>
        <Styled.SignInWrapper>
          <h1>Sign in</h1>
          <Input
            type="text"
            id="username"
            name="username"
            placeholder="Kullanıcı adınızı giriniz"
            label="Kullanıcı Adı"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
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
          {/* Diğer giriş alanlarını buraya ekleyebilirsiniz */}
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
