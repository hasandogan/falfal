import { ReactElement } from 'react';
import FortuneList from '../../components/advanced/Fortune/FortuneList';
import FortuneNotFound from '../../components/advanced/Fortune/FortuneNotFound';
import HomeLogic from '../../hooks/Home.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/home.styled';

const Home = () => {
  const { fortunes } = HomeLogic();

  return (
    <Styled.Home>
      {fortunes?.length === 0 ? (
        <FortuneNotFound />
      ) : (
        <FortuneList fortunes={fortunes} />
      )}
    </Styled.Home>
  );
};

export default Home;

Home.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'HOME'}>{page}</LoggedInLayout>
);
