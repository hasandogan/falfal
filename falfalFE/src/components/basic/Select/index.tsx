import React from 'react';
import Label from '../Label';
import * as Styled from './select.styled';
import SelectProps from './select.type';

const Select: React.FC<SelectProps> = ({ label, options, ...rest }) => {
  return (
    <Styled.Container>
      {label && <Label label={label} />}
      <Styled.Select {...rest}>
        {options.map((option) => (
          <option key={option.value} value={option.value}>
            {option.label}
          </option>
        ))}
      </Styled.Select>
    </Styled.Container>
  );
};

export default Select;
