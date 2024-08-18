import { ReactElement } from 'react';
import { RotatingLines } from 'react-loader-spinner';
import FortuneList from '../../components/advanced/Fortune/FortuneList';
import FortuneNotFound from '../../components/advanced/Fortune/FortuneNotFound';
import ProcessBar from '../../components/advanced/ProcessBar';
import HomeLogic from '../../hooks/Home.logic';
import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/home.styled';

const Home = () => {
  const { isLoading, fortunes, pendingProcess } = HomeLogic();

  const examplePendingProcess = {
    status: true,
    createAt: '2024-08-10 18:56:48',
    endDate: '2024-08-10 18:57:48',
    serverResponseTime: '2024-08-10 18:57:20',
  };

  return (
    <Styled.Home>
      {isLoading ? (
        <div className={'loading-container'}>
          <RotatingLines
            visible={true}
            strokeColor={'#ffffff'}
            strokeWidth="5"
            animationDuration="2"
          />
        </div>
      ) : (
        <>
          {pendingProcess && (
            <ProcessBar pendingProcess={examplePendingProcess} />
          )}
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
