import Link from 'next/link';
import { useRouter } from 'next/router';
import { ReactElement } from 'react';
import Card from '../../components/advanced/Card';
import LoadingContainer from '../../components/advanced/LoadingContainer';
import CoffeeDetailLogic from '../../hooks/CoffeeDetail.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/CoffeeDetail.styled';

const CoffeeDetail = () => {
  const router = useRouter();
  const { id } = router.query;

  const { coffeeResult, isLoading, coffeeRef } = CoffeeDetailLogic(id);

  return (
      <Styled.CoffeeDetail>
        {isLoading ? (
            <LoadingContainer />
        ) : (
            <>
              <Link href={'/home'} className="back">{`< Geri`}</Link>
              <Card className="card-wrapper">
                <h2>{'Kahve Falınız'}</h2>
                <div className="coffee-detail-container" ref={coffeeRef}>
                  {coffeeResult?.images.map((image, index) => (
                      <img
                          key={index}
                          src={image}
                          alt={`Coffee Image ${index}`}
                          className="coffee-image"
                      />
                  ))}
                </div>
                <div className="coffee-message">{coffeeResult?.message}</div>
              </Card>
            </>
        )}
      </Styled.CoffeeDetail>
  );
};

export default CoffeeDetail;

CoffeeDetail.getLayout = (page: ReactElement) => (
    <LoggedInLayout pageName={'Kahve Falı'}>{page}</LoggedInLayout>
);
