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
  position: relative;
  .spin-container {
    position: absolute;
    left: 0;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    margin: 0 auto;
    > div {
      justify-content: center;
    }
  }
  &.loading-active {
    background: linear-gradient(
      223.81deg,
      rgba(0, 229, 229, 0.5) 8.72%,
      rgba(114, 165, 242, 0.5) 50.87%,
      rgba(233, 97, 255, 0.5) 91.3%
    );
  }
`;

export { Button };
