import Link from 'next/link';
import { ReactElement } from 'react';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import Input from '../../components/basic/Input';
import SimpleLayout from '../../layouts/SimpleLayout/SimpleLayout';
import * as Styled from '../../styles/profile.styled';

import { serverSideTranslations } from 'next-i18next/serverSideTranslations';
import { GetServerSideProps } from 'next/types';
import DatePicker from '../../components/basic/Datepicker';
import Select from '../../components/basic/Select';
import ProfileLogic from '../../hooks/Profile.logic';
import { parseCookies } from '../../utils/helpers/parseCookies';

const ProfileSave = () => {
  const {
    handleChange,
    handleSubmit,
    profileData,
    maritalStatusOptions,
    genderOptions,
    employmentStatusOptions,
    hasChildrenOptions,
    educationLevelOptions,
  } = ProfileLogic();

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
            value={profileData.email}
            onChange={handleChange}
          />
          <Input
            type="password"
            id="password"
            name="password"
            placeholder="Şifrenizi giriniz"
            label="Şifre"
            value={profileData.password}
            onChange={handleChange}
          />
          <Select
            id="maritalStatus"
            name="maritalStatus"
            label="İlişki Durumu"
            value={profileData.maritalStatus}
            onChange={handleChange}
            options={maritalStatusOptions}
          />
          <Input
            type="number"
            id="age"
            name="age"
            placeholder="Yaşınızı giriniz"
            label="Yaşınız"
            value={profileData.age}
            onChange={handleChange}
          />
          <Select
            id="gender"
            name="gender"
            label="Cinsiyet"
            value={profileData.gender}
            onChange={handleChange}
            options={genderOptions}
          />
          <Select
            id="hasChildren"
            name="hasChildren"
            label="Çocuk Durumu"
            onChange={handleChange}
            value={profileData.hasChildren}
            options={hasChildrenOptions}
          />
          <DatePicker
            id="birthDate"
            name="birthDate"
            placeholder="Doğum tarihiniz giriniz"
            label="Doğum Tarihi"
            value={profileData.birthDate}
            onChange={handleChange}
          />
          <Input
            type="text"
            id="birthTime"
            name="birthTime"
            placeholder="Doğum saatinizi giriniz"
            label="Doğum Saati"
            value={profileData.birthTime}
            onChange={handleChange}
          />
          <Select
            id="employmentStatus"
            name="employmentStatus"
            label="İş Durumu"
            value={profileData.employmentStatus}
            options={employmentStatusOptions}
            onChange={handleChange}
          />
          <Select
            id="educationLevel"
            name="educationLevel"
            label="Eğitim Durumu"
            value={profileData.educationLevel}
            options={educationLevelOptions}
            onChange={handleChange}
          />
          <Input
            type="text"
            id="occupation"
            name="occupation"
            placeholder="Mesleğinizi giriniz"
            label="Meslek"
            value={profileData.occupation}
            onChange={handleChange}
          />
          <Input
            type="text"
            id="city"
            name="city"
            placeholder="Şehir bilginizi giriniz"
            label="Şehir"
            value={profileData.city}
            onChange={handleChange}
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
