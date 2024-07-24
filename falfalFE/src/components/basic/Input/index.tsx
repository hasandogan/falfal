import React from 'react';
import Label from '../Label';
import * as Styled from './input.styled';
import InputProps from './input.type';

const Input: React.FC<InputProps> = ({ label, ...rest }) => {
  return (
    <Styled.Container>
      {label && <Label label={label} />}
      <Styled.Input {...rest} />
    </Styled.Container>
  );
};

export default Input;
