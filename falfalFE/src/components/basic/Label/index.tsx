import React from 'react';
import * as Styled from './label.styled';
import LabelProps from './label.type';

const Label: React.FC<LabelProps> = ({ label, ...rest }) => {
  return (
    <>
      <Styled.StyledLabel {...rest}>{label}</Styled.StyledLabel>
    </>
  );
};

export default Label;
