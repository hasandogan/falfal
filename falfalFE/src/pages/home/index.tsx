import { ReactElement } from 'react';
import FortuneList from '../../components/advanced/Fortune/FortuneList';
import FortuneNotFound from '../../components/advanced/Fortune/FortuneNotFound';
import LoadingContainer from '../../components/advanced/LoadingContainer';
import ProcessBar from '../../components/advanced/ProcessBar';
import HomeLogic from '../../hooks/Home.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/home.styled';

const Home = () => {
  const { isLoading, fortunes, pendingProcess } = HomeLogic();
  return (
    <Styled.Home>
      {isLoading ? (
        <LoadingContainer />
      ) : (
        <>
          {pendingProcess && <ProcessBar pendingProcess={pendingProcess} />}
          {fortunes?.length === 0 ? (
            <FortuneNotFound />
          ) : (
            <FortuneList fortunes={fortunes} />
          )}
        </>
      )}
    </Styled.Home>
  );
};

export default Home;

Home.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'AnaSayfa'}>{page}</LoggedInLayout>
);
