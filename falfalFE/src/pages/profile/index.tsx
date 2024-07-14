import { IProfileRequest } from '@/services/profile/models/profile/IProfileRequest';
import { Profile } from '@/services/profile/profile';
import Link from 'next/link';
import { ReactElement, useState } from 'react';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import Input from '../../components/basic/Input';
import SimpleLayout from '../../layouts/SimpleLayout/SimpleLayout';
import * as Styled from '../../styles/profile.styled';

import { serverSideTranslations } from 'next-i18next/serverSideTranslations';
import { GetServerSideProps } from 'next/types';
import { parseCookies } from '../../utils/helpers/parseCookies';

const ProfileSave = () => {
  const [email, setEmail] = useState('');
  const [name, setName] = useState('');
  const [lastName, setLastName] = useState('');
  const [password, setPassword] = useState('');
  const [maritalStatus, setMaritalStatus] = useState<
    'married' | 'single' | 'engaged' | 'widowed'
  >('single');
  const [age, setAge] = useState<number | ''>('');
  const [gender, setGender] = useState<'male' | 'female' | 'prefer not to say'>(
    'prefer not to say'
  );
  const [hasChildren, setHasChildren] = useState<'yes' | 'no'>('no');
  const [birthDate, setBirthDate] = useState('');
  const [birthTime, setBirthTime] = useState('');
  const [employmentStatus, setEmploymentStatus] = useState<
    'full-time' | 'part-time' | 'unemployed'
  >('unemployed');
  const [educationLevel, setEducationLevel] = useState<
    | 'primary school'
    | 'secondary school'
    | 'high school'
    | 'vocational school'
    | 'university'
    | 'master`s or doctoral'
  >('primary school');
  const [occupation, setOccupation] = useState('');
  const [city, setCity] = useState('');

  const handleSubmit = async (event) => {
    event.preventDefault();

    const requestData: IProfileRequest = {
      email,
      name,
      lastName,
      password,
      maritalStatus,
      age: age !== '' ? parseInt(age) : undefined,
      gender,
      hasChildren,
      birthDate,
      birthTime,
      employmentStatus,
      educationLevel,
      occupation,
      city,
    };

    try {
      const response = await Profile(requestData);
      console.log('API Response:', response);
      alert('Profil başarıyla güncellendi.');

      // Formu sıfırlamak için state'leri sıfırlayabilirsiniz
      setEmail('');
      setName('');
      setLastName('');
      setPassword('');
      setMaritalStatus('single');
      setAge('');
      setGender('prefer not to say');
      setHasChildren('no');
      setBirthDate('');
      setBirthTime('');
      setEmploymentStatus('unemployed');
      setEducationLevel('primary school');
      setOccupation('');
      setCity('');
    } catch (error) {
      console.error('API Error:', error);
      alert('Profil güncelleme sırasında bir hata oluştu.');
    }
  };

  return (
    <Card type="vertical">
      <form onSubmit={handleSubmit}>
        <Styled.ProfileWrapper>
          <h1>Profile</h1>
          <Input
            type="text"
            id="email"
            name="email"
            placeholder="Kullanıcı adınızı giriniz"
            label="Kullanıcı Adı"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
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
            type="select"
            id="maritalStatus"
            name="maritalStatus"
            label="İlişki Durumu"
            value={maritalStatus}
            onChange={(e) =>
              setMaritalStatus(
                e.target.value as 'married' | 'single' | 'engaged' | 'widowed'
              )
            }
            options={[
              { value: 'married', label: 'Evli' },
              { value: 'single', label: 'Bekar' },
              { value: 'engaged', label: 'Nişanlı' },
              { value: 'widowed', label: 'Dul' },
            ]}
          />
          <Input
            type="number"
            id="age"
            name="age"
            placeholder="Yaşınızı giriniz"
            label="Yaşınız"
            value={age}
            onChange={(e) => setAge(e.target.value)}
          />
          <Input
            type="select"
            id="gender"
            name="gender"
            label="Cinsiyet"
            value={gender}
            onChange={(e) =>
              setGender(
                e.target.value as 'male' | 'female' | 'prefer not to say'
              )
            }
            options={[
              { value: 'male', label: 'Erkek' },
              { value: 'female', label: 'Kadın' },
              { value: 'prefer not to say', label: 'Belirtmek İstemiyorum' },
            ]}
          />
          <Input
            type="select"
            id="hasChildren"
            name="hasChildren"
            label="Çocuk Durumu"
            value={hasChildren}
            onChange={(e) => setHasChildren(e.target.value as 'yes' | 'no')}
            options={[
              { value: 'yes', label: 'Evet' },
              { value: 'no', label: 'Hayır' },
            ]}
          />
          <Input
            type="text"
            id="birthDate"
            name="birthDate"
            placeholder="Doğum tarihinizi giriniz"
            label="Doğum Tarihi"
            value={birthDate}
            onChange={(e) => setBirthDate(e.target.value)}
          />
          <Input
            type="text"
            id="birthTime"
            name="birthTime"
            placeholder="Doğum saatinizi giriniz"
            label="Doğum Saati"
            value={birthTime}
            onChange={(e) => setBirthTime(e.target.value)}
          />
          <Input
            type="select"
            id="employmentStatus"
            name="employmentStatus"
            label="İş Durumu"
            value={employmentStatus}
            onChange={(e) =>
              setEmploymentStatus(
                e.target.value as 'full-time' | 'part-time' | 'unemployed'
              )
            }
            options={[
              { value: 'full-time', label: 'Tam Zamanlı' },
              { value: 'part-time', label: 'Yarı Zamanlı' },
              { value: 'unemployed', label: 'Çalışmıyor' },
            ]}
          />
          <Input
            type="select"
            id="educationLevel"
            name="educationLevel"
            label="Eğitim Durumu"
            value={educationLevel}
            onChange={(e) =>
              setEducationLevel(
                e.target.value as
                  | 'primary school'
                  | 'secondary school'
                  | 'high school'
                  | 'vocational school'
                  | 'university'
                  | 'master`s or doctoral'
              )
            }
            options={[
              { value: 'primary school', label: 'İlköğretim' },
              { value: 'secondary school', label: 'Orta Öğretim' },
              { value: 'high school', label: 'Lise' },
              { value: 'vocational school', label: 'Meslek Yüksek Okulu' },
              { value: 'university', label: 'Üniversite' },
              {
                value: 'master`s or doctoral',
                label: 'Yüksek Lisans ve Doktora',
              },
            ]}
          />
          <Input
            type="text"
            id="occupation"
            name="occupation"
            placeholder="Mesleğinizi giriniz"
            label="Meslek"
            value={occupation}
            onChange={(e) => setOccupation(e.target.value)}
          />
          <Input
            type="text"
            id="city"
            name="city"
            placeholder="Şehir bilginizi giriniz"
            label="Şehir"
            value={city}
            onChange={(e) => setCity(e.target.value)}
          />
          <Button type="submit">Kaydet</Button>
          <div className="separator">
            <div className="separator-text">or sign in using</div>
          </div>
        </Styled.ProfileWrapper>
      </form>
      <Styled.ActionLink>
        Hesabınız yok mu?
        <Link href={'/sign-up'}>Kaydolun</Link>
      </Styled.ActionLink>
    </Card>
  );
};

ProfileSave.getLayout = (page: ReactElement) => (
  <SimpleLayout>{page}</SimpleLayout>
);

export default ProfileSave;

export const getServerSideProps: GetServerSideProps = async (context) => {
  const cookies = parseCookies(context.req.headers.cookie);
  const localeFromCookie = cookies['NEXT_LOCALE'] ?? 'tr';

  return {
    props: {
      ...(await serverSideTranslations(localeFromCookie, ['common'])),
    },
  };
};
