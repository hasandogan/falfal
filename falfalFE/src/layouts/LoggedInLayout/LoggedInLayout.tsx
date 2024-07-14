import Header from '../../components/advanced/Header';
import StickyBar from '../../components/advanced/StickyBar';
import React, { ReactNode } from 'react';
import TempMenu from '../../components/advanced/TempMenu';
import * as Styled from './LoggedInLayout.styled';

type LoggedInLayoutProps = {
  children: ReactNode;
  pageName?: string;
};

const LoggedInLayout: React.FC<LoggedInLayoutProps> = ({
  pageName,
  children,
}) => {
  return (
    <>
      <TempMenu />
      <Styled.LoggedInLayout>
        <Header pageName={pageName!} />
        {children}
        <StickyBar />
      </Styled.LoggedInLayout>
    </>
  );
};

export default LoggedInLayout;
