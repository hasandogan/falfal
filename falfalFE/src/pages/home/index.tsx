import LoggedInLayout from '../../layouts/LoggedInLayout/LoggedInLayout';
import * as Styled from '../../styles/home.styled';

import { ReactElement, useEffect } from 'react';

const Home = () => {
  const getChar = async () => {
    const req = {};
  };
  useEffect(() => {
    getChar();
  }, []);

  return <Styled.Home>Home</Styled.Home>;
};

export default Home;

Home.getLayout = (page: ReactElement) => (
  <LoggedInLayout pageName={'HOME'}>{page}</LoggedInLayout>
);
