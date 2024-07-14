import React from 'react';
import * as Styled from './button.styled';
import ButtonProps from './button.type';

const Button: React.FC<ButtonProps> = ({ type, children, ...rest }) => {
  return (
    <Styled.Button type={type} {...rest}>
      {children}
    </Styled.Button>
  );
};

export default Button;
