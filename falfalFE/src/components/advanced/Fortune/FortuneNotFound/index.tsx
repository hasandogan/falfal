import Link from 'next/link';
import Button from '../../../basic/Button';
import * as Styled from './FortuneNotFound.styled';

const FortuneNotFound = () => {
  return (
    <Styled.FortuneNotFound>
      <div className="title">
        Fal Bakmanın Eğlencesi: Kaderin Anahtarı Sizin Ellerinizde
      </div>
      <div className="description">
        Fal bakmak, hayatınıza eğlenceli bir dokunuş katmanın harika bir
        yoludur. Bu mistik deneyim, günlük rutininizin dışına çıkmanızı sağlar
        ve bazen size ilham verici ipuçları sunabilir. Ancak, unutmayın ki
        kaderiniz yalnızca sizin ellerinizdedir. Fal bakmanın büyüsüne kapılmak
        keyiflidir, ancak gerçek başarılar ve mutluluk, aldığınız kararlarda ve
        attığınız adımlarda yatar. Hayatınıza yön veren sizsiniz ve geleceğinizi
        şekillendirme gücü tamamen size ait. Fal bakmayı bir eğlence olarak
        görüp tadını çıkarın, fakat asıl gücün ve kontrolün sizin elinizde
        olduğunu asla unutmayın. Geleceğinizi inşa etmek için kendi içsel
        gücünüzü ve kararlılığınızı kullanın. Ayrıca profilinizde eksik olan
        bilgilerin doldurulması falın doğruluğu açısından önemlidir. Profilinizi
        güncelleyerek falınızı daha doğru bir şekilde alabilirsiniz.
      </div>
      <div className="links">
        <Link href={'/profile'}>
          <Button>Profilini düzenle</Button>
        </Link>
        <Link href={'/fortune'}>
          <Button>Şimdi Fal baktır.</Button>
        </Link>
      </div>
    </Styled.FortuneNotFound>
  );
};
export default FortuneNotFound;
