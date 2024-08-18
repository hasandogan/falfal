import Link from 'next/link';
import { useRouter } from 'next/router';
import { ReactElement } from 'react';
import Card from '../../components/advanced/Card';
import LoadingContainer from '../../components/advanced/LoadingContainer';
import TarotDetailLogic from '../../hooks/TarotDetail.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/TarotDetail.styled';

const TarotDetail = () => {
  const router = useRouter();
  const { id } = router.query;

  const { tarotResult, isLoading, tarotRef } = TarotDetailLogic(id);

  return (
    <Styled.TarotDetail>
      {isLoading ? (
        <LoadingContainer />
      ) : (
        <>
          <Link href={'/home'} className="back">{`< Back`}</Link>
          <Card className="card-wrapper">
            <h2>{'Tarot Falınız'}</h2>
            <div className="tarot-detail-container" ref={tarotRef}>
              {tarotResult?.selectedCards?.map((card, index) => (
                <div
                  key={index}
                  className={`result-card ${card.result ? '' : 'revert'}`}
                >
                  <img
                    src={card.image}
                    alt={card.name}
                    width={74}
                    height={115}
                  />
                </div>
              ))}
            </div>
            <div className="tarot-message">{tarotResult?.message}</div>
          </Card>
        </>
      )}
    </Styled.TarotDetail>
  );
};

export default TarotDetail;

TarotDetail.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'Tarot Detail'}>{page}</LoggedInLayout>
);
