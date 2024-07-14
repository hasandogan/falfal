import styled from 'styled-components';
import { CardProps } from './card.type';

const Card = styled.div<CardProps>`
  box-shadow: 0px -4px 16px #000000;
  border-radius: 28px;
  padding: 40px;
  background: var(--card-bg);
  padding: ${(props) => (props.type === 'vertical' ? '80px 40px' : '40px')};
`;

export { Card };
