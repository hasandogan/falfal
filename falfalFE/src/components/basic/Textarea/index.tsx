import React from 'react';
import Label from '../Label';
import TextAreaProps from './input.type';
import * as Styled from './textarea.styled';

const TextArea: React.FC<TextAreaProps> = ({ label, ...rest }) => {
  return (
    <Styled.Container>
      {label && <Label label={label} />}
      <Styled.TextArea rows={3} {...rest} />
    </Styled.Container>
  );
};

export default TextArea;
