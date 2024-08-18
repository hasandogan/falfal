import React, { ReactNode } from 'react';
import { ToastContainer } from 'react-toastify';
import Header from '../../components/advanced/Header';
import StickyBar from '../../components/advanced/StickyBar';
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
      <Styled.LoggedInLayout>
        <Header pageName={pageName!} />
        <div className="content">{children}</div>
        <StickyBar />
        <ToastContainer />
      </Styled.LoggedInLayout>
    </>
  );
};

export default LoggedInLayout;
