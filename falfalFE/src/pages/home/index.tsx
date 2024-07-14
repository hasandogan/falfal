import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/home.styled';
import { ReactElement, useEffect, useState } from 'react';
import { useRouter } from 'next/router';
import profile from "@/pages/profile";

const Home = () => {
  const [hasCheckedHoroscope, setHasCheckedHoroscope] = useState(false);
  const router = useRouter();

  const getChar = async () => {
    const userHasCheckedHoroscope = false;
    setHasCheckedHoroscope(userHasCheckedHoroscope);
  };

  useEffect(() => {
    getChar();
  }, []);

  const handleCheckHoroscope = () => {
    router.push('/horoscope');
  };

  return (
      <Styled.Home>
        {hasCheckedHoroscope ? (
            'Home'
        ) : (
            <Styled.Card>
              <Styled.CardTitle>
                Fal Bakmanın Eğlencesi: Kaderin Anahtarı Sizin Ellerinizde

              </Styled.CardTitle>
              <Styled.CardMessage>
                Fal bakmak, hayatınıza eğlenceli bir dokunuş katmanın harika bir yoludur. Bu mistik deneyim, günlük rutininizin dışına çıkmanızı sağlar ve bazen size ilham verici ipuçları sunabilir. Ancak, unutmayın ki kaderiniz yalnızca sizin ellerinizdedir.
                Fal bakmanın büyüsüne kapılmak keyiflidir, ancak gerçek başarılar ve mutluluk, aldığınız kararlarda ve attığınız adımlarda yatar. Hayatınıza yön veren sizsiniz ve geleceğinizi şekillendirme gücü tamamen size ait.
                Fal bakmayı bir eğlence olarak görüp tadını çıkarın, fakat asıl gücün ve kontrolün sizin elinizde olduğunu asla unutmayın. Geleceğinizi inşa etmek için kendi içsel gücünüzü ve kararlılığınızı kullanın.

                Ayrıca profilinizde eksik olan bilgilerin doldurulması falın doğruluğu açısından önemlidir. Profilinizi güncelleyerek falınızı daha doğru bir şekilde alabilirsiniz.
              </Styled.CardMessage>
              <Styled.CardLink onClick={profile}>
                Profilini düzenle
              </Styled.CardLink>
              <br></br>
              <Styled.CardLink onClick={handleCheckHoroscope}>
                Şimdi Fal baktır.
              </Styled.CardLink>
            </Styled.Card>
        )}
      </Styled.Home>
  );
};

export default Home;

Home.getLayout = (page: ReactElement) => (
    <LoggedInLayout pageName={'HOME'}>{page}</LoggedInLayout>
);
