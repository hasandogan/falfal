import { ReactElement } from 'react';
import FortuneList from '../../components/advanced/Fortune/FortuneList';
import FortuneNotFound from '../../components/advanced/Fortune/FortuneNotFound';
import ProcessBar from '../../components/advanced/ProcessBar';
import HomeLogic from '../../hooks/Home.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/home.styled';

const Home = () => {
  const { isLoading, fortunes, pendingProcess } = HomeLogic();

  const dede = {
    status: true,
    createAt: '2024-08-10 18:56:48',
    endDate: '2024-08-10 19:26:48',
    serverResponseTime: '2024-08-10 19:12:47',
  };

  return (
    <Styled.Home>
      {isLoading ? (
        <>spin</>
      ) : (
        <>
          {pendingProcess && <ProcessBar pendingProcess={dede} />}
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
  <LoggedInLayout pageName={'HOME'}>{page}</LoggedInLayout>
);
