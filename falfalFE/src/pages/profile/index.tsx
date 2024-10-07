import { ReactElement } from 'react';
import Card from '../../components/advanced/Card';
import Button from '../../components/basic/Button';
import Input from '../../components/basic/Input';
import * as Styled from '../../styles/profile.styled';

import { serverSideTranslations } from 'next-i18next/serverSideTranslations';
import { GetServerSideProps } from 'next/types';
import LoadingContainer from '../../components/advanced/LoadingContainer';
import DatePicker from '../../components/basic/Datepicker';
import Select from '../../components/basic/Select';
import ProfileLogic from '../../hooks/Profile.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import { parseCookies } from '../../utils/helpers/parseCookies';

const Profile = () => {
  const {
    handleChange,
    handleSubmit,
    profileData,
    relationShipOptions,
    genderOptions,
    jobStatusOptions,
    hasChildrenOptions,
    educationLevelOptions,
    isLoading,
    isPageLoading,
  } = ProfileLogic();
  return (
    <Card type="vertical">
      {isPageLoading ? (
        <>
          <LoadingContainer />
        </>
      ) : (
        <form onSubmit={handleSubmit}>
          <Styled.ProfileWrapper>
            <h1>Profil</h1>
            <Input
              type="text"
              id="name"
              name="name"
              placeholder="name"
              label="Adınız"
              value={profileData.name}
              onChange={handleChange}
            />
            <Input
                type="text"
                id="lastName"
                name="lastName"
                placeholder="lastName"
                label="Adınız"
                value={profileData.lastName}
                onChange={handleChange}
            />
            <Input
              type="text"
              id="email"
              name="email"
              placeholder="email"
              label="Email adresi"
              value={profileData.email}
              onChange={handleChange}
            />
            {/* <Input
            type="password"
            id="password"
            name="password"
            placeholder="Şifrenizi giriniz"
            label="Şifre"
            value={profileData.password}
            onChange={handleChange}
          /> */}
            <Select
              id="relationShip"
              name="relationShip"
              label="İlişki Durumu"
              value={profileData.relationShip}
              onChange={handleChange}
              options={relationShipOptions}
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
              id="jobStatus"
              name="jobStatus"
              label="İş Durumu"
              value={profileData.jobStatus}
              options={jobStatusOptions}
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
              id="town"
              name="town"
              placeholder="Şehir bilginizi giriniz"
              label="Şehir"
              value={profileData.town}
              onChange={handleChange}
            />
            <Input
              type="text"
              id="country"
              name="country"
              placeholder="Ülke bilginizi girini"
              label="Ülke"
              value={profileData.country}
              onChange={handleChange}
            />
            <Button type="submit" loading={isLoading} disabled={isLoading}>
              Kaydet
            </Button>
          </Styled.ProfileWrapper>
        </form>
      )}
    </Card>
  );
};

Profile.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'Profil'}>{page}</LoggedInLayout>
);

export default Profile;

export const getServerSideProps: GetServerSideProps = async (context) => {
  const cookies = parseCookies(context.req.headers.cookie);
  const localeFromCookie = cookies['NEXT_LOCALE'] ?? 'tr';

  return {
    props: {
      ...(await serverSideTranslations(localeFromCookie, ['common'])),
    },
  };
};
