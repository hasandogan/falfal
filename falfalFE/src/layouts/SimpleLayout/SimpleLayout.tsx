import React from 'react';
import { ToastContainer } from 'react-toastify';
import Logo from '../../components/advanced/Logo';
import * as Styled from './SimpleLayout.styled';
import { SimpleLayoutProps } from './SimpleLayout.type';

const SimpleLayout: React.FC<SimpleLayoutProps> = ({ children }) => {
  return (
    <>
      <Styled.SimpleLayout>
        <Logo />
        <div className="simple-layout-content">{children}</div>
        <ToastContainer />
      </Styled.SimpleLayout>
    </>
  );
};

export default SimpleLayout;
