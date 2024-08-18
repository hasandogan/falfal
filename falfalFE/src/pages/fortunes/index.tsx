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
        <Link href={fortune.url} key={`${fortune.name}-${index}`}>
          <Card className={'card'}>
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
          </Card>
        </Link>
      ))}
    </Styled.Fortunes>
  );
};
export default Fortunes;

Fortunes.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'Fallar'}>{page}</LoggedInLayout>
);
