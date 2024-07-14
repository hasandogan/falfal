import styled from 'styled-components';
import ButtonProps from './button.type';

const Button = styled.button<ButtonProps>`
  padding: 10px;
  background: linear-gradient(
    223.81deg,
    #00e5e5 8.72%,
    #72a5f2 50.87%,
    #e961ff 91.3%
  );
  outline: none;
  border: none;
  border-radius: 44px;
  font-size: 24px;
  line-height: 24px;
  color: var(--white);
`;

export { Button };
