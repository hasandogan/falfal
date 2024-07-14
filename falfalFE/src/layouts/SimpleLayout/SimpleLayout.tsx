import Logo from '../../components/advanced/Logo';
import React from 'react';
import TempMenu from '../../components/advanced/TempMenu';
import * as Styled from './SimpleLayout.styled';
import { SimpleLayoutProps } from './SimpleLayout.type';

const SimpleLayout: React.FC<SimpleLayoutProps> = ({ children }) => {
  return (
    <>
      <TempMenu />
      <Styled.SimpleLayout>
        <Logo />
        <div className="simple-layout-content">{children}</div>
      </Styled.SimpleLayout>
    </>
  );
};

export default SimpleLayout;
