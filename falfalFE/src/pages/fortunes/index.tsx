import Image from 'next/image';
import Link from 'next/link';
import { ReactElement } from 'react';
import Card from '../../components/advanced/Card';
import FortunesLogic from '../../hooks/Fortunes.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/fortunes.styled';

const Fortunes = () => {
  const { fortuneList } = FortunesLogic();
  return (
    <Styled.Fortunes>
      {fortuneList?.map((fortune, index) => (
        <Card className={'card'} key={`${fortune.name}-${index}`}>
          <div className="card-top">
            <div className="card-top-left">
              <Image
                src={fortune.imageUrl}
                alt={fortune.name}
                width={60}
                height={60}
              />
            </div>
            <div className="card-top-right">
              <div className="name">{fortune.name}</div>
              <div className="description">{fortune.description}</div>
            </div>
          </div>
          <div className="card-bottom">
            <Link href={fortune.url}>Take a look</Link>
          </div>
        </Card>
      ))}
    </Styled.Fortunes>
  );
};
export default Fortunes;

Fortunes.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'FORTUNES'}>{page}</LoggedInLayout>
);
