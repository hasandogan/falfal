import React from 'react';
import * as Styled from './input.styled';
import InputProps from './input.type';

const Input: React.FC<InputProps> = ({ label, ...rest }) => {
  return <Styled.Input {...rest} />;
};

export default Input;
