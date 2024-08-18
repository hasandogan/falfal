import React from 'react';
import { TailSpin } from 'react-loader-spinner';
import * as Styled from './button.styled';
import ButtonProps from './button.type';

const Button: React.FC<ButtonProps> = ({
  type,
  children,
  loading,
  ...rest
}) => {
  return (
    <Styled.Button
      type={type}
      {...rest}
      className={`${rest.className} ${loading ? 'loading-active' : ''}`}
    >
      {loading && (
        <div className="spin-container">
          <TailSpin width={24} height={24} color={'#0a0339'} />
        </div>
      )}
      {children}
    </Styled.Button>
  );
};

export default Button;
